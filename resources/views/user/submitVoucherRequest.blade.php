@extends("app" , ["pageName" => "submitRequest"])

@section("headPageLevelPlugin")
    <link href="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-toastr/toastr-rtl.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/extra/persian-datepicker/dist/css/persian-datepicker-0.4.5.css" rel="stylesheet" type="text/css"/>
@endsection

@section("headPageLevelStyle")
    <link rel="stylesheet" href="{{ mix('/css/page_level_style_all.css') }}">
@endsection

@section("bodyClass")
    class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-sidebar-closed page-md"
@endsection

@section("metadata")
    @parent()
    <meta name="_token" content="{{ csrf_token() }}">
@endsection

@section("pageBar")
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="{{action("HomeController@index")}}">خانه</a>
                <i class="fa fa-angle-left"></i>
            </li>
            <li>
                <span>ثبت درخواست اینترنت آسیاتک</span>
            </li>
        </ul>
    </div>
@endsection

@section("content")
    {{--EXCHANGE LOTTERY--}}
    {{--<div class="row">--}}
        {{--<div class="col-md-12">--}}
                {{--<div class="alert alert-block bg-blue bg-font-blue fade in">--}}
                    {{--<button type="button" class="close" data-dismiss="alert"></button>--}}
                    {{--<h4 class="alert-heading text-center" style="line-height: normal;">نام شما در شرکت کنندگان همایش رایگان حضوری عربی آقای ناصح زاده روز 27 خرداد ثبت شده است</h4>--}}
                    {{--<h4 class="alert-heading text-center" style="line-height: normal;">برای انصراف از شرکت در همایش بر روی دکمه زیر کلیک کنید</h4>--}}
                    {{--<p style="text-align: center;">--}}
                        {{--<button class="btn mt-sweetalert-hamayesh-arabi" data-title="آیا از شرکت خود مطمئنید؟" data-type="warning" data-allow-outside-click="true" data-show-confirm-button="true" data-show-cancel-button="true" data-cancel-button-class="btn-danger" data-cancel-button-text="خیر" data-confirm-button-text="بله شرکت می کنم" data-confirm-button-class="btn-info" style="background: #d6af18;">ثبت نام در همایش حضوری</button>--}}
                        {{--<button class="btn btn-lg" id="bt-cancel-hamayesh-arabi"  style="background: #d6af18;">انصراف می دهم</button>--}}
                    {{--</p>--}}
                {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PROFILE SIDEBAR -->
            <div class="profile-sidebar">
                {{--ToDo: customzing photo layout for supporting jquery upload--}}
                @include('partials.profileSidebar',['user'=>$user , 'withInfoBox'=>true , 'withPhotoUpload' => true ])
            </div>
            <!-- END BEGIN PROFILE SIDEBAR -->
            <!-- BEGIN PROFILE CONTENT -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-purple-intense bold uppercase">
                                                    ثبت درخواست اینترنت رایگان آسیاتک
                                    </span>
                                </div>
                            </div>

                            <div class="portlet-body">
                                    @include('user.profile.profileEditView' , ["withBio"=>false, "withBirthdate"=>true , "withIntroducer"=>true , "submitCaption" => "ثبت" , "text1"=>"لطفا اطلاعات خود را با دقت و صحت کامل تکمیل نمایید و سپس بر روی ثبت درخواست کلیک کنید . در صورت صحیح و کامل بودن اطلاعات در خواست شما در صف بررسی قرار می گیرد و وضعیت آن از طریق همین صفحه قابل مشاهده خواهد بود." , "text2"=>" اطلاعات وارد شده پس از ثبت درخواست قابل تغییر نیستند . <a href='#'>برای دیدن نمونه های صحیح اطلاعات اینجا کلیک کنید</a>" ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->

        </div>
    </div>
@endsection

@section("footerPageLevelPlugin")
    <script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script src="/assets/extra/persian-datepicker/lib/persian-date.js" type="text/javascript" ></script>
@endsection

@section("footerPageLevelScript")
    <script src="/assets/pages/scripts/profile.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/ui-toastr.min.js" type="text/javascript"></script>
    <script src="/assets/extra/persian-datepicker/dist/js/persian-datepicker-0.4.5.min.js" type="text/javascript" ></script>
@endsection

@section("extraJS")
    <script type="text/javascript">
        /**
         * Set token for ajax request
         */
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                }
            });
        });
        jQuery(document).ready(function() {
            $("#birthdate").persianDatepicker({
                altField: '#birthdateAlt',
                altFormat: "YYYY MM DD",
                observer: true,
                format: 'YYYY/MM/DD',
                altFieldFormatter: function (unixDate) {
                    var d = new Date(unixDate).toISOString();
                    return d;
                }
            });
        });

        var Upload = function (file) {
            this.file = file;
        };

        Upload.prototype.getType = function() {
            return this.file.type;
        };
        Upload.prototype.getSize = function() {
            return this.file.size;
        };
        Upload.prototype.getName = function() {
            return this.file.name;
        };
        Upload.prototype.doUpload = function () {
            var formData = new FormData();
            $("#profilePhotoAjaxLoadingSpinner").show();
            // add assoc key values, this will be posts values
            formData.append("photo", this.file, this.getName());
            // formData.append("upload_file", true);
            $.ajax({
                type: "POST",
                url: "{{action("UserController@updatePhoto")}}",
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        // For handling the progress of the upload
                        myXhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                $('progress').attr({
                                    value: e.loaded,
                                    max: e.total,
                                });
                            }
                        } , false);
                    }
                    return myXhr;
                },
                statusCode: {
                    //The status for when action was successful
                    200: function (response) {
                        // console.log(response);
                        $("#profilePhoto").attr("src" , response.newPhoto);
                        $("#profilePhotoAjaxFileInputClose").trigger("click");
                    },
                    //The status for when the user is not authorized for making the request
                    403: function (response) {
                        window.location.replace("/403");
                    },
                    //The status for when the user is not authorized for making the request
                    401: function (response) {
                        window.location.replace("/403");
                    },
                    404: function (response) {
                        window.location.replace("/404");
                    },
                    //The status for when form data is not valid
                    422: function (response) {
                        console.log(response);
                    },
                    //The status for when there is error php code
                    500: function (response) {
                        console.log(response.responseText);
//                            toastr["error"]("خطای برنامه!", "پیام سیستم");
                    },
                    //The status for when there is error php code
                    503: function (response) {
//                            toastr["error"]("خطای پایگاه داده!", "پیام سیستم");
                    }
                },
                async: true,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                // timeout: 60000,
            });

            $("#profilePhotoAjaxLoadingSpinner").hide();
        };

        Upload.prototype.progressHandling = function (event) {
            var percent = 0;
            var position = event.loaded || event.position;
            var total = event.total;
            var progress_bar_id = "#progress-wrp";
            if (event.lengthComputable) {
                percent = Math.ceil(position / total * 100);
            }
            // update progressbars classes so it fits your code
            $(progress_bar_id + " .progress-bar").css("width", +percent + "%");
            $(progress_bar_id + " .status").text(percent + "%");
        };

        //Change id to your id
        $("#profilePhotoAjaxForm").on("submit", function (e) {
            e.preventDefault();
            var file = $("#profilePhotoFile")[0].files[0];
            var upload = new Upload(file);

            // maby check size or type here with upload.getSize() and upload.getType()

            // execute upload
            upload.doUpload();
        });
    </script>
@endsection
