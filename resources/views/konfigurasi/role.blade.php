@extends('layouts.master')

@push('css')
<link href="{{ asset('vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="main-content">
    <div class="title">
        Konfigurasi
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Roles</h4>
                    </div>
                    <div class="card-body">
                        {{-- jika user memiliki permision create maka tampilkan tombol tambah --}}
                        @if (request()->user()->can('create konfigurasi/roles')) 
                        <button type="button" class="btn mb-2 btn-primary btn-sm btn-add"><i class="ti-plus"> Tambah Data</i></button>
                        @endif
                       {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mymodal" tabindex="-1"
        aria-labelledby="extraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/datatables.min.js') }}"></script>
<script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    {{ $dataTable->scripts() }}

{{-- aksi untuk modal pada edit dan tambah role --}}
<script>
    const myModal = new bootstrap.Modal($('#mymodal'));

    function store(){
            $('#formAction').on('submit', function(e){
                e.preventDefault();
                const _form = this;
                const formData = new FormData(_form);

                const url = this.getAttribute('action'); //membuat url dinamis ketika create atau updated

                $.ajax({
                    method: 'POST',
                    url,
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //ambil csrf token di header (master.blade.php)
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res){
                        window.LaravelDataTables["role-table"].ajax.reload(); //ketika data berhasil di udah reload ulang data tablenya
                        myModal.hide();
                    },
                    error: function(err){
                        //mengambil pesan eror validasi pada edit data
                        let errors = err.responseJSON?.errors;
                        //hapus dulu text eror validasi jika ada
                        $(_form).find('.text-danger.text-small').remove();
                        if(errors){ //jika ada respon validasi error maka looping dan berikan tag html baru dengan respon ke inputnya yang sesuai dengan keynya atau namenya
                            for(const [key, value] of Object.entries(errors)){
                                $(`[name="${key}"]`).parent().append(`<span class="text-danger text-small">${value}</span>`);
                            }
                        }
                    }
                })

            });
        }


    $('.btn-add').on('click', function(e){
        $.ajax({
            method: 'GET',
            url: `{{ url('konfigurasi/roles/create') }}`,
            success:function(res){
                $('#mymodal').find('.modal-dialog').html(res);
                myModal.show();
                store();
            }
        });
    });

    $('#role-table').on('click','.action', function(){
        let data = $(this).data();
        let id = data.id;
        let jenis = data.jenis;

        if(jenis == 'delete'){ //jika aksi delete maka tampilkan swict alert 
                Swal.fire({
                    title:"Are you sure?",
                    text:"You won't be able to revert this!",
                    icon:"warning",
                    showCancelButton:!0,
                    confirmButtonColor:"#3085d6",
                    cancelButtonColor:"#d33",
                    confirmButtonText:"Yes, delete it!"
                }).then(t=>{
                    if( t.isConfirmed){
                        //jika yakin menghapus data maka kirim data yang dihapus menggunakan ajax dan membahwa id serta token csrf
                        $.ajax({
                            method:'DELETE',
                            url:`{{ url('konfigurasi/roles') }}/${id}`,
                            headers:{
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //ambil csrf token di header (master.blade.php)
                            },
                            success:function(res){
                                window.LaravelDataTables["role-table"].ajax.reload(); //ketika data berhasil di udah reload ulang data tablenya
                               Swal.fire("Deleted!",res.message,res.status);
                            }
                        });
                    }
                })
            
                return
        }

        // menampilkan form modal ketika mau di edit data
        $.ajax({
            method :'GET',
            url: `{{ url('konfigurasi/roles') }}/${id}/edit`,
            success:function(res){
                $('#mymodal').find('.modal-dialog').html(res);
                myModal.show();
                store();
            }
        });

    })
</script>
@endpush