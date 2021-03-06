@if(isset($attributevalue))
    {!! Form::hidden('id',$attributevalue->id, ['class' => 'btn red']) !!}
    <div class = "form-body">
        <div class = "note note-warning">
            <h4 class = "caption-subject font-dark bold uppercase">
                وارد کردن اطلاعات زیر الزامیست:
            </h4>
        </div>
        <div class = "form-group {{ $errors->has('name') ? ' has-danger' : '' }}">
            <div class = "row">
                <label class = "col-md-3 control-label" for = "name">نام مقدار صفت</label>
                <div class = "col-md-9">
                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name' ]) !!}
                    @if ($errors->has('name'))
                        <span class="form-control-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <br>
        <div class = "note note-info">
            <h4 class = "caption-subject font-dark bold uppercase">
                وارد کردن اطلاعات زیر اختیاری می باشد:
            </h4>
        </div>
        <div class = "form-group {{ $errors->has('description') ? ' has-danger' : '' }}">
            <div class = "row">
                <label class = "col-md-3 control-label" for = "description">توضیح مقدار صفت</label>
                <div class = "col-md-9">
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'description']) !!}
                    @if ($errors->has('description'))
                        <span class="form-control-feedback">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class = "form-actions">
            <div class = "row">
                <div class = "col-md-offset-3 col-md-9">
                    {!! Form::submit('اصلاح', ['class' => 'btn btn-warning m-btn m-btn--icon m-btn--wide']) !!}
                </div>
            </div>
        </div>
    </div>
@else
    <div class = "col-md-12">
        <p class = "caption-subject font-dark bold uppercase"> وارد کردن اطلاعات زیر الزامی می باشد:</p>
    </div>
    <div class = "col-md-8 col-md-offset-2">
        <p>
            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'attributevalueName' , 'placeholder'=>'نام مقدار صفت']) !!}
            <span class="form-control-feedback" id = "attributevalueNameAlert">
                <strong></strong>
            </span>
        </p>
    </div>
    <div class = "col-md-12">
        <p class = "caption-subject font-dark bold uppercase"> وارد کردن اطلاعات زیر اختیاری می باشد:</p>
    </div>
    <div class = "col-md-8 col-md-offset-2">
        <p>
            {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'attributevalueDescription'  , 'placeholder'=>'توضیح درباره مقدار صفت']) !!}
            <span class="form-control-feedback" id = "attributevalueDescriptionAlert">
                <strong></strong>
            </span>
        </p>
    </div>
    {!! Form::hidden('attribute_id',$attribute->id) !!}
@endif