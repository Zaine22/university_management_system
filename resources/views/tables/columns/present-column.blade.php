@php
    // dd($getRecord()->id);
    $datas = $column->getData($getRecord()->id);
@endphp

<div>
    @foreach ($datas as $data)
        <div style="margin-right: 15px; font-size: 13px">
            {{ $data['batch_name'] }}
            <div style="display: flex">
                <div style="margin-right: 15px">
                    {{ $data['present_count'] }}
                </div>
                <div style="margin-right: 15px">
                    {{ $data['leave_count'] }}
                </div>
                <div>
                    {{ $data['absent_count'] }}
                </div>
            </div>
        </div>
    @endforeach
</div>
