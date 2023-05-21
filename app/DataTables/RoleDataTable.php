<?php

namespace App\DataTables;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn() //menambahkan kolom untuk nomor
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y H:i:s'); //mengubah format tanggal created att
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->format('d-m-Y H:i:s'); //mengubah format tanggal updated att
            })
            ->addColumn('action', function ($row) {
                $action = '';
                if (Gate::allows('update konfigurasi/roles')) { // cek apakah user mempunyai permision update maka tampilkan aksinya
                    $action = '<button type="button" data-id="' . $row->id . '" data-jenis="edit" class="btn mb-2 btn-primary btn-sm action"><i class="ti-pencil"></i></button>'; //menambah tombol aksi edit
                }

                if (Gate::allows('delete konfigurasi/roles')) { //cek apakah user mempunyai permision delete maka tampilkan aksi delet
                    $action .= '<button type="button" data-id="' . $row->id . '" data-jenis="delete" class="btn mb-2 btn-danger btn-sm action"><i class="ti-trash"></i></button>'; //menambah tombol aksi hapus
                }
                return $action;
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->parameters(['searchDelay' => 1500]) //membuat waktu delay ketika melakukan search pada fitur search
            ->setTableId('role-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1);
        // ->selectStyleSingle()
        // ->buttons([
        //     Button::make('excel'),
        //     Button::make('csv'),
        //     Button::make('pdf'),
        //     Button::make('print'),
        //     Button::make('reset'),
        //     Button::make('reload')
        // ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            // Column::make('id'),
            Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false), //mengubah title kolom no disablekan order dan serach untuk nomor
            Column::make('name'), //mengambil data name pada role
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Role_' . date('YmdHis');
    }
}
