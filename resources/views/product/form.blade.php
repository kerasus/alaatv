@if(isset($product))
    <div class="form-group">
        <div class="row">
            <label class="col-md-3 control-label" for="name">نوع محصول</label>
            <div class="col-md-9">
                <text class="form-control-static bold"> {{$producttype}} </text>
            </div>
        </div>
    </div>
    @if(!$product->hasParents())
        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            <div class="row">
                <label class="col-md-3 control-label" for="name">نام کالا</label>
                <div class="col-md-9">
                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name' ]) !!}
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group {{ $errors->has('order') ? ' has-error' : '' }}">
            <div class="row">
                <label class="col-md-3 control-label" for="name">ترتیب</label>
                <div class="col-md-9">
                    {!! Form::text('order', null, ['class' => 'form-control', 'placeholder'=>'ترتیب']) !!}
                    @if ($errors->has('order'))
                        <span class="help-block">
                            <strong>{{ $errors->first('order') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-body">
            <div class="form-group {{ $errors->has('file') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="col-md-3 control-label" for="file">کاتالوگ</label>
                    <div class="col-md-9">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="input-group input-large ">
                                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                    <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                    <span class="fileinput-filename"> @if(isset($product->file) && strlen($product->file)>0) {{$product->file}} @endif</span>
                                </div>
                                <span class="input-group-addon btn default btn-file">
                                                                        <span class="fileinput-new"> انتخاب فایل </span>
                                                                        <span class="fileinput-exists"> تغییر </span>
                                    {!! Form::file('file' , ['id'=>'file']) !!} </span>
                                <a href="javascript:" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> حذف</a>
                            </div>
                        </div>
                        @if ($errors->has('file'))
                            <span class="help-block">
                                <strong>{{ $errors->first('file') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="control-label col-md-3">عکس</label>
                    <div class="col-md-9">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="{{ $product->photo }}" {{--alt="عکس خدمت" onerror="this.src='http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image';"/> </div>--}}@if(strlen($product->name)>0) alt="{{$product->name}}" @else alt="عکس محصول" @endif/>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                            <div>
                                <span class="btn default btn-file">
                                    <span class="fileinput-new btn m-btn--pill m-btn--air btn-warning"> تغییر عکس </span>
                                    <span class="fileinput-exists m-btn--pill m-btn--air btn-warning"> تغییر </span>
                                    <input type="file" name="image">
                                </span>
                                <a href="javascript:" class="btn red fileinput-exists" id="userPhoto-remove" data-dismiss="fileinput">
                                    حذف
                                </a>
                            </div>
                        </div>
                        @if ($errors->has('image'))
                            <span class="help-block">
                                <strong>{{ $errors->first('image') }}</strong>
                            </span>
                        @endif
                        {{--<div class="clearfix margin-top-10">--}}
                        {{--<span class="m-badge m-badge--wide m-badge--danger">توجه</span> فرمت های مجاز: jpg , png - حداکثر حجم مجاز: ۲۰۰KB </div>--}}
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('introVideo') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="col-md-3 control-label" for="slogan">لینک فیلم معرفی</label>
                    <div class="col-md-9">
                        {!! Form::text('introVideo', null, ['class' => 'form-control', 'id' => 'introVideo' , 'dir' => 'ltr' ]) !!}
                        @if ($errors->has('introVideo'))
                            <span class="help-block">
                            <strong>{{ $errors->first('introVideo') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <br>
            <div class="form-group {{ $errors->has('basePrice') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="col-md-3 control-label" for="basePrice">قیمت پایه</label>
                    <div class="col-md-9">
                        {!! Form::text('basePrice', null, ['class' => 'form-control', 'id' => 'basePrice' , 'dir'=>'ltr' ]) !!}
                        @if ($errors->has('basePrice'))
                            <span class="help-block">
                            <strong>{{ $errors->first('basePrice') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt-checkbox-list">
                            <label class="mt-checkbox mt-checkbox-outline m--font-danger bold">یکسان سازی قیمت فرزندان
                                <input type="checkbox" value="1" name="changeChildrenPrice"/>
                                <span></span>
                            </label>
                            <label class="mt-checkbox mt-checkbox-outline">رایگان باشد
                                <input type="checkbox" value="1" name="isFree" @if($product->isFree) checked @endif />
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('discount') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="col-md-3 control-label" for="discount">تخفیف (%)</label>
                    <div class="col-md-9">
                        {!! Form::text('discount', null, ['class' => 'form-control', 'id' => 'discount', 'dir'=>'ltr' ]) !!}
                        @if ($errors->has('discount'))
                            <span class="help-block">
                            <strong>{{ $errors->first('discount') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('amount') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="col-md-3 control-label" for="amount">تعداد موجود</label>
                    <div class="col-md-9">
                        {!! Form::text('amount', null, ['class' => 'form-control', 'id' => 'amount']) !!}
                        @if ($errors->has('amount'))
                            <span class="help-block">
                            <strong>{{ $errors->first('amount') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('amount') ? ' has-error' : '' }}">
                <div class="row">
                    <div class="clearfix margin-top-20 col-md-9 col-md-offset-3">
                        {!! Form::select('amountLimit',$amountLimit, $defaultAmountLimit, ['class' => 'form-control', 'id' => 'amountLimit']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('enable') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="col-md-3 control-label" for="enable">وضعیت</label>
                    <div class="col-md-9">
                        {!! Form::select('enable',$enableStatus,$defaultEnableStatus,['class' => 'form-control', 'id' => 'enable']) !!}
                        @if ($errors->has('enable'))
                            <span class="help-block">
                                <strong>{{ $errors->first('enable') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('attributeset_id') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="col-md-3 control-label" for="attributeset_id">دسته صفت</label>
                    <div class="col-md-9">
                        {!! Form::select('attributeset_id',$attributesets,null,['class' => 'form-control', 'id' => 'attributeset_id']) !!}
                        @if ($errors->has('attributeset_id'))
                            <span class="help-block">
                            <strong>{{ $errors->first('attributeset_id') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('slogan') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="col-md-3 control-label" for="slogan">اسلوگان</label>
                    <div class="col-md-9">
                        {!! Form::text('slogan', null, ['class' => 'form-control', 'id' => 'slogan' ]) !!}
                        @if ($errors->has('slogan'))
                            <span class="help-block">
                            <strong>{{ $errors->first('slogan') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @if(isset($bons))
                {!! Form::hidden('bon_id', $bons->id) !!}
                <div class="form-group {{ $errors->has('bonPlus') ? ' has-error' : '' }}">
                    <div class="row">
                        <label class="col-md-3 control-label" for="bonPlus">تعداد بن افزوده محصول</label>
                        <div class="col-md-9">
                            @if(isset($bons->pivot->bonPlus))
                                {!! Form::text('bonPlus', $bons->pivot->bonPlus, ['class' => 'form-control', 'id' => 'productBonPlus' ]) !!}
                            @else
                                {!! Form::text('bonPlus', null, ['class' => 'form-control', 'id' => 'productBonPlus' ]) !!}
                            @endif
                            @if ($errors->has('productBonPlus'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('productBonPlus') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('bonDiscount') ? ' has-error' : '' }}">
                    <div class="row">
                        <label class="col-md-3 control-label" for="bonDiscount">میزان تخفیف برای هر بن (%)</label>
                        <div class="col-md-9">
                            @if(isset($bons->pivot->discount))
                                {!! Form::text('bonDiscount', $bons->pivot->discount, ['class' => 'form-control', 'id' => 'productBonDiscount' ]) !!}
                            @else
                                {!! Form::text('bonDiscount', null, ['class' => 'form-control', 'id' => 'productBonDiscount' ]) !!}
                            @endif
                            @if ($errors->has('bonDiscount'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('bonDiscount') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            <br>
            <div class="form-group {{ $errors->has('shortDescription') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="col-md-3 control-label" for="shortDescription">توضیحات مختصر</label>
                    <div class="col-md-9">
                        {{--<div name="summernote" id="summernote_1">{!! $product->description !!}</div>--}}
                        {!! Form::textarea('shortDescription', null, ['class' => 'form-control', 'id' => 'productShortDescriptionSummerNote' ]) !!}
                        @if ($errors->has('shortDescription'))
                            <span class="help-block">
                                <strong>{{ $errors->first('shortDescription') }}</strong>
                             </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('longDescription') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="col-md-3 control-label" for="longDescription">توضیحات اجمالی</label>
                    <div class="col-md-9">
                        {{--<div name="summernote" id="summernote_1">{!! $product->description !!}</div>--}}
                        {!! Form::textarea('longDescription', null, ['class' => 'form-control', 'id' => 'productLongDescriptionSummerNote' ]) !!}
                        @if ($errors->has('longDescription'))
                            <span class="help-block">
                                <strong>{{ $errors->first('longDescription') }}</strong>
                             </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('specialDescription') ? ' has-error' : '' }}">
                <div class="row">
                    <label class="col-md-3 control-label" for="specialDescription">توضیحات خاص</label>
                    <div class="col-md-9">
                        {{--<div name="summernote" id="summernote_1">{!! $product->description !!}</div>--}}
                        {!! Form::textarea('specialDescription', null, ['class' => 'form-control', 'id' => 'productSpecialDescriptionSummerNote' ]) !!}
                        @if ($errors->has('specialDescription'))
                            <span class="help-block">
                                <strong>{{ $errors->first('specialDescription') }}</strong>
                             </span>
                        @endif
                    </div>
                </div>
            </div>
            @include('product.partials.tagsInput' )
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        {!! Form::submit('اصلاح', ['class' => 'btn m-btn--pill m-btn--air btn-primary' ] ) !!}
                    </div>
                </div>
            </div>
        </div>
    @else
        {!! Form::hidden("name" , $product->name) !!}
        {!! Form::hidden("attributeset_id" , $product->attributeset_id) !!}
        <div class="form-group {{ $errors->has('basePrice') ? ' has-error' : '' }}">
            <div class="row">
                <label class="col-md-3 control-label" for="basePrice">قیمت پایه</label>
                <div class="col-md-9">
                    {!! Form::text('basePrice', null, ['class' => 'form-control', 'id' => 'basePrice' , 'dir'=>'ltr' ]) !!}
                    @if ($errors->has('basePrice'))
                        <span class="help-block">
                                            <strong>{{ $errors->first('basePrice') }}</strong>
                                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline">رایگان باشد
                            <input type="checkbox" value="1" name="isFree" @if($product->isFree) checked @endif />
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
            <div class="row">
                <label class="control-label col-md-3">عکس</label>
                <div class="col-md-9">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                            <img src="{{ $product->photo }}" {{--alt="عکس خدمت" onerror="this.src='http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image';"/> </div>--}}@if(strlen($product->name)>0) alt="{{$product->name}}" @else  alt="عکس محصول" @endif/>
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div>
                            <span class="btn default btn-file">
                                <span class="fileinput-new btn m-btn--pill m-btn--air btn-warning"> تغییر عکس </span>
                                <span class="fileinput-exists m-btn--pill m-btn--air btn-warning"> تغییر </span>
                                <input type="file" name="image">
                            </span>
                            <a href="javascript:" class="m-btn--pill m-btn--air btn-danger fileinput-exists" id="userPhoto-remove" data-dismiss="fileinput"> حذف</a>
                        </div>
                    </div>
                    @if ($errors->has('image'))
                        <span class="help-block">
                            <strong>{{ $errors->first('image') }}</strong>
                        </span>
                    @endif
                    {{--<div class="clearfix margin-top-10">--}}
                    {{--<span class="m-badge m-badge--wide m-badge--danger">توجه</span> فرمت های مجاز: jpg , png - حداکثر حجم مجاز: ۲۰۰KB </div>--}}
                </div>
            </div>
        </div>
        <div class="form-group {{ $errors->has('discount') ? ' has-error' : '' }}">
            <div class="row">
                <label class="col-md-3 control-label" for="discount">تخفیف (%)</label>
                <div class="col-md-9">
                    {!! Form::text('discount', null, ['class' => 'form-control', 'id' => 'discount', 'dir'=>'ltr' ]) !!}
                    @if ($errors->has('discount'))
                        <span class="help-block">
                            <strong>{{ $errors->first('discount') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <br>
        <div class="form-group {{ $errors->has('shortDescription') ? ' has-error' : '' }}">
            <div class="row">
                <label class="col-md-3 control-label" for="shortDescription">توضیحات مختصر</label>
                <div class="col-md-9">
                    {!! Form::textarea('shortDescription', null, ['class' => 'form-control', 'id' => 'productShortDescriptionSummerNote' ]) !!}
                    @if ($errors->has('shortDescription'))
                        <span class="help-block">
                                            <strong>{{ $errors->first('shortDescription') }}</strong>
                                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group {{ $errors->has('longDescription') ? ' has-error' : '' }}">
            <div class="row">
                <label class="col-md-3 control-label" for="longDescription">توضیحات اجمالی</label>
                <div class="col-md-9">
                    {!! Form::textarea('longDescription', null, ['class' => 'form-control', 'id' => 'productLongDescriptionSummerNote' ]) !!}
                    @if ($errors->has('longDescription'))
                        <span class="help-block">
                                            <strong>{{ $errors->first('longDescription') }}</strong>
                                        </span>
                    @endif
                </div>
            </div>
        </div>
        @include('product.partials.tagsInput' )
        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-3 col-md-9">
                    {!! Form::submit('اصلاح', ['class' => 'btn m-btn--pill m-btn--air btn-primary' ] ) !!}
                </div>
            </div>
        </div>
    @endif
@else
    <div class="row">
        <div class="col-md-6">
            <p>
                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'productName' , 'placeholder'=>'نام کالا']) !!}
                <span class="help-block" id="productNameAlert">
                <strong></strong>
            </span>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                @if(isset($defaultProductOrder))
                    {!! Form::text('order', $defaultProductOrder, ['class' => 'form-control', 'id' => 'productOrder' , 'placeholder'=>'ترتیب']) !!}
                @else
                    {!! Form::text('order', null, ['class' => 'form-control', 'id' => 'productOrder' , 'placeholder'=>'ترتیب']) !!}
                @endif
                <span class="help-block" id="productOrderAlert">
                    <strong></strong>
                </span>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                {!! Form::text('basePrice', null, ['class' => 'form-control', 'id' => 'productBasePrice' , 'placeholder'=>'قیمت پایه']) !!}
                <span class="help-block" id="productBasePriceAlert">
                    <strong></strong>
                </span>
                <label class="control-label" style="float: right;">
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" id="isFree" value="1" name="isFree">
                        <span class="bg-grey-cararra"></span>
                    </label>
                    رایگان
                </label>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                {!! Form::text('discount', null, ['class' => 'form-control', 'id' => 'productDiscount' , 'placeholder'=>'تخفیف (%)']) !!}
                <span class="help-block" id="productDiscountAlert">
                    <strong></strong>
                </span>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                {!! Form::text('slogan', null, ['class' => 'form-control', 'id' => 'productSlogan', 'placeholder'=>'اسلوگان']) !!}
                <span class="help-block" id="productSloganAlert">
                    <strong></strong>
                </span>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                {!! Form::select('attributeset_id',$attributesets,null,['class' => 'form-control', 'id' => 'productAttributeSetID' , 'placeholder'=>'انتخاب دسته صفت']) !!}
                <span class="help-block" id="productAttributeSetIDAlert">
                    <strong></strong>
                </span>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                {!! Form::select('enable',$enableStatus, null,['class' => 'form-control', 'id' => 'productEnable']) !!}
                <span class="help-block" id="productEnableAlert">
                    <strong></strong>
                </span>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                {!! Form::text('amount', null, ['class' => 'form-control', 'id' => 'productAmount'  , 'placeholder'=>'تعداد موجود']) !!}
                <span class="help-block" id="productAmountAlert">
                            <strong></strong>
                    </span>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                {!! Form::select('amountLimit',$limitStatus, null, ['class' => 'form-control', 'id' => 'amountLimit']) !!}
            </p>
        </div>
        {!! Form::hidden('bon_id', 1) !!}
        <div class="col-md-6">
            <p>
                {!! Form::text('bonPlus', null, ['class' => 'form-control', 'id' => 'productBonPlus', 'placeholder' => 'تعداد بن محصول']) !!}
                <span class="help-block" id="productBonPlusAlert">
                    <strong></strong>
                </span>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                {!! Form::text('bonDiscount', null, ['class' => 'form-control', 'id' => 'productBonDiscount', 'placeholder' => 'میزان تخفیف برای هر بن (%)']) !!}
                <span class="help-block" id="productBonDiscountAlert">
                    <strong></strong>
                </span>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                {!! Form::select('producttype_id', $productTypes, null, ['class' => 'form-control', 'id' => 'producttypeId', 'placeholder'=>'نوع محصول']) !!}
                <span class="help-block" id="producttypeIdAlert">
                    <strong></strong>
                </span>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                {!! Form::text('introVideo', null, ['class' => 'form-control', 'id' => 'productIntroVideo', 'placeholder'=>'لینک فیلم معرفی' , 'dir'=>'ltr']) !!}
                <span class="help-block" id="productIntroVideoAlert">
                    <strong></strong>
                </span>
            </p>
        </div>

        <div class="col-md-6">
            <div class="fileinput fileinput-new" id="productFile-div" data-provides="fileinput">
                <div class="input-group input-large ">
                    <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                        <i class="fa fa-file fileinput-exists"></i>&nbsp;
                        <span class="fileinput-filename"> </span>
                    </div>
                    <span class="input-group-addon btn default btn-file">
                        <span class="fileinput-new"> انتخاب کاتالوگ </span>
                        <span class="fileinput-exists"> تغییر </span>
                        {!! Form::file('file' , ['id'=>'productFile']) !!}
                    </span>
                    <a href="javascript:" class="input-group-addon btn red fileinput-exists" id="productFile-remove" data-dismiss="fileinput"> حذف</a>
                </div>
            </div>
            <span class="help-block" id="productFileAlert">
                <strong></strong>
            </span>
        </div>
        <div class="col-md-6">
            <div class="fileinput fileinput-new" id="image-div" data-provides="fileinput">
                <div class="input-group input-large">
                    <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                        <i class="fa fa-file fileinput-exists"></i>
                        <span class="fileinput-filename"> </span>
                    </div>
                    <div>
                        <span class="input-group-addon btn default btn-file">
                            <span class="fileinput-new"> انتخاب عکس </span>
                            <span class="fileinput-exists"> تغییر </span>
                            {!! Form::file('image' , ['id'=>'productImage']) !!}
                        </span>

                        <a href="javascript:" class="btn red fileinput-exists" id="productImage-remove" data-dismiss="fileinput"> حذف</a>
                    </div>
                    <div class="clearfix margin-top-10">
                        <span class="m-badge m-badge--wide m-badge--danger">توجه</span>
                        <strong id="productImageAlert">فرمت های مجاز: jpg , png - حداکثر حجم مجاز: ۲۰۰KB</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <label class="col-md-3 control-label" for="shortDescription">توضیحات مختصر</label>
            <br>
            <p>
                {!! Form::textarea('shortDescription', null, ['class' => 'form-control', 'id' => 'productShortDescriptionSummerNote' , 'placeholder'=>'توضیح مختصر']) !!}
                <span class="help-block" id="productShortDescriptionSummerNoteAlert">
                    <strong></strong>
                </span>
            </p>
        </div>
        <div class="col-md-12">
            <label class="col-md-3 control-label" for="longDescription">توضیحات اجمالی</label>
            <br>
            <p>
                {!! Form::textarea('longDescription', null, ['class' => 'form-control', 'id' => 'productLongDescriptionSummerNote' , 'placeholder'=>'توضیح اجمالی']) !!}
                <span class="help-block" id="productLongDescriptionSummerNoteAlert">
                    <strong></strong>
                </span>
            </p>
        </div>
    </div>
@endif
