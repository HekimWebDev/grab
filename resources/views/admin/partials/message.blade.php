@if(session()->has('error'))
<div class="" style="margin: 35px 15px 0 268px;">
    @if(session()->get('message'))
        <div class="alert alert-default-success">Есть изменении в ценах</div
    @else
        <div class="alert alert-default-danger">Нет изменений в ценах</div>
    @endif
</div>
@endif

@if(session()->has('error'))
<div class="" style="margin: 35px 15px 0 268px;">
    @if(session()->get('message'))
        <div class="alert alert-default-success">Есть изменении в ценах</div
    @else
        <div class="alert alert-default-danger">Нет изменений в ценах</div>
    @endif
</div>
@endif