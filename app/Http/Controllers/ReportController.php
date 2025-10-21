<?php

namespace App\Http\Controllers;

use App\Models\Representado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:representados,users,combined',
            'columns' => 'required|array|min:1',
            'format' => 'required|in:csv',
            'filters' => 'nullable|array',
        ]);

        try {
            $columns = $request->columns;
            $filters = $request->filters ?? [];
            $reportType = $request->report_type;

            switch ($reportType) {
                case 'representados':
                    $data = $this->generateRepresentadosReport($columns, $filters);
                    $filename = 'reporte_representados_' . date('Y-m-d_H-i-s') . '.csv';
                    break;
                
                case 'users':
                    $data = $this->generateUsersReport($columns, $filters);
                    $filename = 'reporte_usuarios_' . date('Y-m-d_H-i-s') . '.csv';
                    break;
                
                case 'combined':
                    $data = $this->generateCombinedReport($columns, $filters);
                    $filename = 'reporte_combinado_' . date('Y-m-d_H-i-s') . '.csv';
                    break;
                
                default:
                    return back()->with('error', 'Tipo de reporte no válido');
            }

            return $this->exportToCsv($data, $filename);

        } catch (\Exception $e) {
            \Log::error('Error generando reporte: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    private function generateRepresentadosReport($columns, $filters)
    {
        $query = Representado::with('user');

        // Aplicar filtros
        if (!empty($filters['nivel_academico'])) {
            $query->where('nivel_academico', $filters['nivel_academico']);
        }

        if (!empty($filters['fecha_desde'])) {
            $query->whereDate('created_at', '>=', $filters['fecha_desde']);
        }

        if (!empty($filters['fecha_hasta'])) {
            $query->whereDate('created_at', '<=', $filters['fecha_hasta']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $representados = $query->get();

        $data = [];
        $headers = [];

        // Definir headers basados en columnas seleccionadas
        $columnMap = [
            'representado_id' => 'ID Representado',
            'representado_nombres' => 'Nombres Representado',
            'representado_apellidos' => 'Apellidos Representado',
            'representado_ci' => 'CI Representado',
            'representado_fecha_nacimiento' => 'Fecha Nacimiento',
            'representado_telefono' => 'Teléfono Representado',
            'representado_direccion' => 'Dirección Representado',
            'representado_nivel_academico' => 'Nivel Académico',
            'representado_fecha_registro' => 'Fecha Registro',
            'representante_id' => 'ID Representante',
            'representante_nombres' => 'Nombres Representante',
            'representante_apellidos' => 'Apellidos Representante',
            'representante_ci' => 'CI Representante',
            'representante_email' => 'Email Representante',
            'representante_telefono' => 'Teléfono Representante',
            'representante_direccion' => 'Dirección Representante',
            'representante_fecha_registro' => 'Fecha Registro Representante',
        ];

        foreach ($columns as $column) {
            if (isset($columnMap[$column])) {
                $headers[] = $columnMap[$column];
            }
        }

        $data[] = $headers;

        // Llenar datos
        foreach ($representados as $representado) {
            $row = [];
            
            foreach ($columns as $column) {
                $row[] = $this->getRepresentadoValue($representado, $column);
            }
            
            $data[] = $row;
        }

        return $data;
    }

    private function generateUsersReport($columns, $filters)
    {
        $query = User::withCount('representados');

        // Aplicar filtros
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (!empty($filters['perfil_completo'])) {
            if ($filters['perfil_completo'] == 'completo') {
                $query->whereNotNull('ci')
                      ->whereNotNull('nombres')
                      ->whereNotNull('apellidos')
                      ->whereNotNull('telefono')
                      ->whereNotNull('direccion');
            } else {
                $query->where(function($q) {
                    $q->whereNull('ci')
                      ->orWhereNull('nombres')
                      ->orWhereNull('apellidos')
                      ->orWhereNull('telefono')
                      ->orWhereNull('direccion');
                });
            }
        }

        if (!empty($filters['fecha_desde'])) {
            $query->whereDate('created_at', '>=', $filters['fecha_desde']);
        }

        if (!empty($filters['fecha_hasta'])) {
            $query->whereDate('created_at', '<=', $filters['fecha_hasta']);
        }

        $users = $query->get();

        $data = [];
        $headers = [];

        $columnMap = [
            'user_id' => 'ID Usuario',
            'user_ci' => 'CI Usuario',
            'user_nombres' => 'Nombres',
            'user_apellidos' => 'Apellidos',
            'user_email' => 'Email',
            'user_telefono' => 'Teléfono',
            'user_direccion' => 'Dirección',
            'user_fecha_nacimiento' => 'Fecha Nacimiento',
            'user_vive_con_representado' => 'Vive con Representado',
            'user_role' => 'Rol',
            'user_perfil_completo' => 'Perfil Completo',
            'user_cantidad_representados' => 'Cantidad de Representados',
            'user_fecha_registro' => 'Fecha Registro',
        ];

        foreach ($columns as $column) {
            if (isset($columnMap[$column])) {
                $headers[] = $columnMap[$column];
            }
        }

        $data[] = $headers;

        foreach ($users as $user) {
            $row = [];
            
            foreach ($columns as $column) {
                $row[] = $this->getUserValue($user, $column);
            }
            
            $data[] = $row;
        }

        return $data;
    }

    private function generateCombinedReport($columns, $filters)
    {
        $query = Representado::with('user');

        // Aplicar filtros
        if (!empty($filters['nivel_academico'])) {
            $query->where('nivel_academico', $filters['nivel_academico']);
        }

        if (!empty($filters['role'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('role', $filters['role']);
            });
        }

        if (!empty($filters['fecha_desde'])) {
            $query->whereDate('representados.created_at', '>=', $filters['fecha_desde']);
        }

        if (!empty($filters['fecha_hasta'])) {
            $query->whereDate('representados.created_at', '<=', $filters['fecha_hasta']);
        }

        $representados = $query->get();

        $data = [];
        $headers = [];

        $columnMap = [
            'representado_id' => 'ID Representado',
            'representado_nombres' => 'Nombres Representado',
            'representado_apellidos' => 'Apellidos Representado',
            'representado_ci' => 'CI Representado',
            'representado_fecha_nacimiento' => 'Fecha Nacimiento Representado',
            'representado_telefono' => 'Teléfono Representado',
            'representado_direccion' => 'Dirección Representado',
            'representado_nivel_academico' => 'Nivel Académico',
            'representado_fecha_registro' => 'Fecha Registro Representado',
            'user_id' => 'ID Representante',
            'user_ci' => 'CI Representante',
            'user_nombres' => 'Nombres Representante',
            'user_apellidos' => 'Apellidos Representante',
            'user_email' => 'Email Representante',
            'user_telefono' => 'Teléfono Representante',
            'user_direccion' => 'Dirección Representante',
            'user_fecha_nacimiento' => 'Fecha Nacimiento Representante',
            'user_role' => 'Rol Representante',
            'user_perfil_completo' => 'Perfil Completo Representante',
            'user_fecha_registro' => 'Fecha Registro Representante',
        ];

        foreach ($columns as $column) {
            if (isset($columnMap[$column])) {
                $headers[] = $columnMap[$column];
            }
        }

        $data[] = $headers;

        foreach ($representados as $representado) {
            $row = [];
            
            foreach ($columns as $column) {
                if (str_starts_with($column, 'representado_')) {
                    $row[] = $this->getRepresentadoValue($representado, $column);
                } else {
                    $row[] = $this->getUserValue($representado->user, $column);
                }
            }
            
            $data[] = $row;
        }

        return $data;
    }

    private function getRepresentadoValue($representado, $column)
    {
        switch ($column) {
            case 'representado_id':
                return $representado->id;
            case 'representado_nombres':
                return $this->escapeCsvValue($representado->nombres);
            case 'representado_apellidos':
                return $this->escapeCsvValue($representado->apellidos);
            case 'representado_ci':
                return $this->escapeCsvValue($representado->ci ?? 'N/A');
            case 'representado_fecha_nacimiento':
                return $representado->fecha_nacimiento ? $representado->fecha_nacimiento->format('d/m/Y') : 'N/A';
            case 'representado_telefono':
                return $this->escapeCsvValue($representado->telefono ?? 'N/A');
            case 'representado_direccion':
                return $this->escapeCsvValue($representado->direccion ?? 'N/A');
            case 'representado_nivel_academico':
                return $this->escapeCsvValue($representado->nivel_academico);
            case 'representado_fecha_registro':
                return $representado->created_at->format('d/m/Y H:i:s');
            case 'representante_id':
                return $representado->user->id;
            case 'representante_nombres':
                return $this->escapeCsvValue($representado->user->nombres);
            case 'representante_apellidos':
                return $this->escapeCsvValue($representado->user->apellidos);
            case 'representante_ci':
                return $this->escapeCsvValue($representado->user->ci ?? 'N/A');
            case 'representante_email':
                return $this->escapeCsvValue($representado->user->email);
            case 'representante_telefono':
                return $this->escapeCsvValue($representado->user->telefono ?? 'N/A');
            case 'representante_direccion':
                return $this->escapeCsvValue($representado->user->direccion ?? 'N/A');
            case 'representante_fecha_registro':
                return $representado->user->created_at->format('d/m/Y H:i:s');
            default:
                return 'N/A';
        }
    }

    private function getUserValue($user, $column)
    {
        switch ($column) {
            case 'user_id':
                return $user->id;
            case 'user_ci':
                return $this->escapeCsvValue($user->ci ?? 'N/A');
            case 'user_nombres':
                return $this->escapeCsvValue($user->nombres);
            case 'user_apellidos':
                return $this->escapeCsvValue($user->apellidos);
            case 'user_email':
                return $this->escapeCsvValue($user->email);
            case 'user_telefono':
                return $this->escapeCsvValue($user->telefono ?? 'N/A');
            case 'user_direccion':
                return $this->escapeCsvValue($user->direccion ?? 'N/A');
            case 'user_fecha_nacimiento':
                return $user->fecha_nacimiento ? $user->fecha_nacimiento->format('d/m/Y') : 'N/A';
            case 'user_vive_con_representado':
                return $user->vive_con_representado ? 'Sí' : 'No';
            case 'user_role':
                return $user->role === 'admin' ? 'Administrador' : 'Usuario';
            case 'user_perfil_completo':
                return $user->perfil_completo ? 'Sí' : 'No';
            case 'user_cantidad_representados':
                return $user->representados_count ?? $user->representados()->count();
            case 'user_fecha_registro':
                return $user->created_at->format('d/m/Y H:i:s');
            default:
                return 'N/A';
        }
    }

    private function escapeCsvValue($value)
    {
        if ($value === null) {
            return '';
        }
        
        $value = (string) $value;
        
        // Si el valor contiene comas, comillas o saltos de línea, encerrar en comillas
        if (preg_match('/[,"\n\r]/', $value)) {
            $value = '"' . str_replace('"', '""', $value) . '"';
        }
        
        return $value;
    }

    private function exportToCsv($data, $filename)
    {
        // Limpiar cualquier output anterior
        if (ob_get_length()) {
            ob_clean();
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 para Excel
            fwrite($file, "\xEF\xBB\xBF");
            
            foreach ($data as $row) {
                fputcsv($file, $row, ',', '"', "\\");
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}