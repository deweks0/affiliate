@extends('layouts.app', ['activePage' => 'commission', 'titlePage' => __('Commission')])

@section('content')
<div id="preloaders" class="preloader"></div>
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Commision</h4>
                <p class="category">Commision</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">

                    <table class="table">
                        <thead>
                            <tr class="text-center text-primary text-bold">
                                <th>#</th>
                                <th>Reseller</th>
                                <th>Month</th>
                                <th>Total Client</th>
                                <th>Total Payment</th>
                                <th>Total Commision</th>
                                <th>Percentage</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commissions as $commission)
                            <tr class="text-center">
                                <td>{{$loop->index +1}}</td>
                                <td>{{$commission->user->name}}</td>
                                <td>{{$commission->created_at->format('M-Y')}}</td>
                                @php
                                    $clients = $commission->user->clients;
                                    $totalClient=[];
                                    foreach ($clients as $client) {
                                        foreach ($client->transactions as $transaction ) {
                                            if (Carbon\Carbon::parse($transaction->payment_date)->format('m-Y') == $commission->created_at->format('m-Y')) {
                                                array_push($totalClient,$transaction);
                                            }
                                        }
                                    }
                                    $totalClient = count($totalClient);
                                @endphp
                                <td>{{ $totalClient }}</td>
                                <td>{{$commission->total_payment}}</td>
                                <td>{{$commission->total_commission}}</td>
                                <td>{{$commission->percentage}}%</td>
                                <td>
                                  @if ($commission->status == true)
                                  <a rel="tooltip" class="btn btn-success btn-fab btn-fab-mini btn-round " href="">
                                  <i class="material-icons">done</i>
                                  <div class="ripple-container"></div>
                              </a>
                              @else
                              <a rel="tooltip" class="btn btn-danger btn-fab btn-fab-mini btn-round " href="">
                              <i class="material-icons">clear</i>
                              <div class="ripple-container"></div>
                          </a>
                                  @endif
                                </td>
                                <td>
                                    <a rel="tooltip" class="btn btn-info btn-fab btn-fab-mini btn-round detail" href=""
                                        data-original-title="" data-placement="bottom" title="detail"
                                        data-month="{{$commission->created_at->format('m')}}"
                                        data-loop="{{$loop->index +1}}"
                                        data-year="{{ $commission->created_at->format('Y') }}"
                                        data-user="{{ $commission->user->id }}" data-toggle="modal"
                                        data-target="#detailModal-{{$loop->index+1}}">
                                        <i class="material-icons">list</i>
                                        <div class="ripple-container"></div>
                                    </a>
                                    @if ($commission->status == false)
                                        
                                    <a rel="tooltip" class="btn btn-success btn-fab btn-fab-mini btn-round detail"
                                    href="" data-original-title="" data-placement="bottom"
                                    title="upload transaction evidence" data-toggle="modal"
                                    data-target="#file-upload-Modal-{{$loop->index+1}}">
                                    <i class="material-icons">upload</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  @else
                                    <a rel="tooltip" class="btn btn-primary btn-fab btn-fab-mini btn-round detail"
                                    href="" data-original-title="" data-placement="bottom"
                                    title="show transaction evidence " data-toggle="modal"
                                    data-target="#show-image-Modal-{{$loop->index+1}}">
                                    <i class="material-icons">image</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  @endif
                                </td>
                            </tr>
                            {{-- detail modal --}}
                            <div class="modal fade" id="detailModal-{{$loop->index +1}}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Detail Modal</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row  mx-3">
                                                <div class="col-6">
                                                    <p>Commission Id : {{$commission->id}}</p>
                                                    <p>Issue date : {{$commission->created_at}}</p>
                                                    <p>Summary </p>
                                                </div>
                                                <div class="col-6">
                                                    <p>From : {{$commission->company->name}}</p>
                                                    <p>Status : {{$commission->status ?'paid':"doesn't paid"}}</p>
                                                </div>
                                            </div>
                                            {{-- head --}}
                                            <div class="mx-auto w-75">
                                                
                                                <div class="row text-center align-items-center">
                                                    <div class=" col-1 border py-2 border-dark text-primary ">
                                                        <b>
                                                            <h5 class="my-auto">#</h5>
                                                        </b>
                                                    </div>
                                                    <div class=" col-3 border py-2 border-dark text-primary ">
                                                        <b>
                                                            <h5 class="my-auto">Name</h5>
                                                        </b>
                                                    </div>
                                                    <div class=" col-4 border py-2 border-dark text-primary ">
                                                        <b>
                                                            <h5 class="my-auto">Company</h5>
                                                        </b>
                                                    </div>
                                                    <div class=" col-4 border py-2 border-dark text-primary ">
                                                        <b>
                                                            <h5 class="my-auto">Payment</h5>
                                                        </b>
                                                    </div>

                                                    {{-- content --}}
                                                    <div id="show-detail-{{$loop->index+1}}"></div>
                                                    
                                                    {{-- end content --}}
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end flex-column mx-5 my-3">
                                                <p>
                                                    Total Payment : {{$commission->total_payment}}
                                                </p>
                                                <p>
                                                    Commission : {{$commission->percentage}}%
                                                </p>
                                                <p>
                                                    Total Commission : {{$commission->total_commission}}
                                                </p>
                                            </div>
                                            {{-- <div id="show-detail-{{$loop->index+1}}">
                                            </div> --}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- upload evidance modal --}}
                            <div class="modal fade" id="file-upload-Modal-{{$loop->index +1}}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog " role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Detail Modal</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{route('admin.commissions.update',$commission->id)}}"
                                            method="post" role="form" id="myuseredit" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                @method('PUT')
                                                @csrf
                                                <div class="d-flex justify-content-center">
                                                  <img id="uploadPreview" src="http://style.anu.edu.au/_anu/4/images/placeholders/person_8x10.png" class=" border border-dark p-1" style="max-width: 500px; max-height: 300px;" />
                                                </div>
                                                <div class="form-group form-file-upload form-file-multiple">
                                                    <input type="file" name="image" id="uploadImage" class="inputFileHidden"
                                                        onchange="PreviewImage();" accept="image/x-png,image/jpeg,image/x-png,image/jjif">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control inputFileVisible"
                                                            placeholder="Single File">
                                                        <span class="input-group-btn">
                                                            <button type="button"
                                                                class="btn btn-fab btn-round btn-primary">
                                                                <i class="material-icons">attach_file</i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                                {!!$errors->first('image', '<span class="text-danger">:message</span>')!!}

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- upload evidance modal --}}
                            <div class="modal fade" id="show-image-Modal-{{$loop->index +1}}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog " role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Detail Modal</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                            <div class="modal-body ">
                                                <div class="d-flex justify-content-center p-2 border  bg-dark   ">
                                                    <img src="{{ asset('/storage/evidence/'.$commission->photo_path) }}" style="max-width: 516px; max-height:400px" class="h-100 "alt="">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script>
    // form file upload
    
    function PreviewImage() {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);

        oFReader.onload = function (oFREvent) {
            document.getElementById("uploadPreview").src = oFREvent.target.result;
        };
    };
    $('table').DataTable()
    $("#preloaders").fadeOut(1000);
    md.initDashboardPageCharts();
    $('.detail').click(function () {
        const month = $(this).data('month')
        const year = $(this).data('year')
        const user_id = $(this).data('user')
        // console.log()
        $.get(`{{url('/')}}/reseller/commision-month?user_id=${user_id}&month=${month}&year=${year}`, (res) => {
            let card = $(`#show-detail-${$(this).data('loop')}`)
            // card.html("");
            console.log('res',res)
            $('.t-column').remove()
            res.map((el,index) => {
                console.log(el.name, el.data)
                let show =
                    `
                    <div class="col-1 py-1 border border-dark t-column border-top-0  ${index+1 !==res.length ?'border-bottom-0':''}" ${index%2 ==0?'style="background:#e9ecef"':''}>
                        <p class="my-auto">${index+1}</p>
                    </div>
                    <div class="col-3 py-1 border border-dark t-column border-top-0  ${index+1 !==res.length ?'border-bottom-0':''}" ${index%2 ==0?'style="background:#e9ecef"':''}>
                        <p class="my-auto">${el.name}</p>
                    </div>
                    <div class="col-4 py-1 border border-dark t-column border-top-0  ${index+1 !==res.length ?'border-bottom-0':''}" ${index%2 ==0?'style="background:#e9ecef"':''}>
                        <p class="my-auto">${el.company}</p>
                    </div>
                    <div class="col-4 py-1 border border-dark t-column border-top-0  ${index+1 !==res.length ?'border-bottom-0':''}" ${index%2 ==0?'style="background:#e9ecef"':''}>
                        <p class="my-auto">Rp.${el.transaction}</p>
                    </div>
                `
                card.before(show)
            })
        })
    })
    $('.form-file-simple .inputFileVisible').click(function() {
    $(this).siblings('.inputFileHidden').trigger('click');
  });
    // file input
      $('.form-file-simple .inputFileHidden').change(function() {
        var filename = $(this).val().replace(/C:\\fakepath\\/i, '');
        $(this).siblings('.inputFileVisible').val(filename);
      });

      $('.form-file-multiple .inputFileVisible, .form-file-multiple .input-group-btn').click(function() {
        $(this).parent().parent().find('.inputFileHidden').trigger('click');
        $(this).parent().parent().addClass('is-focused');
      });

      $('.form-file-multiple .inputFileHidden').change(function() {
        var names = '';
        for (var i = 0; i < $(this).get(0).files.length; ++i) {
          if (i < $(this).get(0).files.length - 1) {
            names += $(this).get(0).files.item(i).name + ',';
          } else {
            names += $(this).get(0).files.item(i).name;
          }
        }
        $(this).siblings('.input-group').find('.inputFileVisible').val(names);
      });

      $('.form-file-multiple .btn').on('focus', function() {
        $(this).parent().siblings().trigger('focus');
      });
    
      $('.form-file-multiple .btn').on('focusout', function() {
        $(this).parent().siblings().trigger('focusout');
      });
    // file input end
</script>
@endpush