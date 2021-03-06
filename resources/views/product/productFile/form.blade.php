@if(!isset($productFile))
    {!! Form::hidden("product_id" , $product->id) !!}
@endif
<div class = "form-group {{ $errors->has('name') ? ' has-danger' : '' }} col-md-12">
    <div class = "row">
        <label class = "col-md-3 control-label" for = "productFileName">نام فایل</label>
        <div class = "col-md-9">
            {!! Form::text('name', old('name') , ['class' => 'form-control' , 'id'=>'productFileName']) !!}
            @if ($errors->has('name'))
                <span class="form-control-feedback">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
            @endif
        </div>
    </div>
</div>
<div class = "form-group {{ $errors->has('productfiletype_id') ? ' has-danger' : '' }} col-md-12">
    <div class = "row">
        <label class = "col-md-3 control-label" for = "productfileType_id">نوع فایل</label>
        <div class = "col-md-9">
            {!! Form::select('productfiletype_id' , $productFileTypes, null, ['class' => 'form-control' , 'id'=>'productFileTypeSelect'  ]) !!}
            @if ($errors->has('productfiletype_id'))
                <span class="form-control-feedback">
                <strong>{{ $errors->first('productfiletype_id') }}</strong>
            </span>
            @endif
        </div>
    </div>
</div>
<div class = "form-group {{ $errors->has('order') ? ' has-danger' : '' }} col-md-12">
    <div class = "row">
        <label class = "col-md-3 control-label" for = "productFileOrder">ترتیب فایل</label>
        <div class = "col-md-9">
            {!! Form::text('order', old('order'), ['class' => 'form-control' , 'id'=>'productFileOrder' , 'dir'=>'ltr']) !!}
            @if ($errors->has('order'))
                <span class="form-control-feedback">
                <strong>{{ $errors->first('order') }}</strong>
            </span>
            @endif
        </div>
    </div>
</div>
<div class = "form-group {{ $errors->has('file') ? ' has-danger' : '' }} col-md-12">
    <div class = "row">
        <label class = "col-md-3 control-label" for = "productFile">فایل</label>
        <div class = "col-md-9">
            <div class = "fileinput fileinput-new" data-provides = "fileinput">
                <div class = "input-group input-large ">
                    <div class = "form-control uneditable-input input-fixed input-medium" data-trigger = "fileinput">
                        <i class = "fa fa-file fileinput-exists"></i>&nbsp;
                        <span class = "fileinput-filename"> @if(isset($productFile->file)) {{$productFile->file}} @endif</span>
                    </div>
                    <span class = "input-group-addon btn default btn-file">
                        <span class = "fileinput-new btn btn-info m-btn m-btn--icon m-btn--wide"> انتخاب فایل </span>
                        <span class = "fileinput-exists btn btn-warning m-btn m-btn--icon m-btn--wide"> تغییر </span>
                        {!! Form::file('file' , ['id'=>'productFile']) !!} </span>
                    <a href = "javascript:" class = "input-group-addon fileinput-exists btn btn-danger m-btn m-btn--icon m-btn--wide" data-dismiss = "fileinput">
                        حذف
                    </a>
                </div>
            </div>
            @if ($errors->has('file'))
                <span class="form-control-feedback">
                    <strong>{{ $errors->first('file') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
<div class = "form-group {{ $errors->has('cloudFile') ? ' has-danger' : '' }} col-md-12">
    <div class = "row">
        <label class = "col-md-3 control-label" for = "cloudFile">لینک خارجی فایل</label>
        <div class = "col-md-9">
            {!! Form::text('cloudFile', old('cloudFile'), ['class' => 'form-control' , 'dir' => 'ltr']) !!}
            @if ($errors->has('cloudFile'))
                <span class="form-control-feedback">
                    <strong>{{ $errors->first('cloudFile') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
<div class = "form-group {{ $errors->has('description') ? ' has-danger' : '' }} col-md-12">
    <div class = "row">
        <label class = "col-md-3 control-label" for = "productFileDescription">توضیح</label>
        <div class = "col-md-9">
            {!! Form::textarea('description', old('description'), ['class' => 'form-control' , 'id'=>'productFileDescription' , 'rows'=>'3']) !!}
            @if ($errors->has('description'))
                <span class="form-control-feedback">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
<div class = "form-group {{ $errors->has('validSinceDate') ? ' has-danger' : '' }} col-md-12">
    <div class = "row">
        <label class = " col-md-3 control-label" for = "productFileValidSince">تاریخ نمایان شدن(برای کاربر)</label>
        <div class = "col-md-9">
            <input id = "productFileValidSince" type = "text" class = "form-control" value = "@if(isset($validDate)) {{$validDate}} @else {{old('validSinceDate')}} @endif" dir = "ltr">
            <input name = "validSinceDate" id = "productFileValidSinceAlt" type = "text" class = "form-control d-none">
        </div>
    </div>
</div>
<br>
<div class = "form-group {{ $errors->has('time') ? ' has-danger' : '' }} col-md-12">
    <div class = "row">
        <label class = "col-md-3 control-label" for = "productFileValidSinceTime">ساعت</label>
        <div class = "col-md-9">
            <input class = "form-control" name = "time" id = "productFileValidSinceTime" placeholder = "00:00" value = "@if(isset($validTime)){{$validTime}} @else {{old('time')}}@endif" dir = "ltr">
        </div>
    </div>
</div>
<div class = "form-group {{ $errors->has('enable') ? ' has-danger' : '' }} col-md-12">
    <div class = "row">
        <label class = "col-md-3"></label>
        <div class = "col-md-9">
            <div class = "mt-checkbox-list">
                <label class = "mt-checkbox mt-checkbox-outline bold"> فعال بودن
                    <input type = "checkbox" value = "1" name = "enable" @if(isset($productFile->enable) && $productFile->enable == 1) checked @endif/>
                    <span></span>
                </label>
            </div>
        </div>
    </div>
</div>
