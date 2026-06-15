{{--
    Transaction detail key-value table.
    Usage: @include('emails.partials.transaction-details', ['details' => [
        ['label' => 'Amount', 'value' => '$500.00'],
        ['label' => 'Status', 'value' => 'Confirmed'],
    ]])
--}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" class="detail-table"
       style="margin: 20px 0; border: 1px solid #E5E7EB; border-radius: 6px; border-collapse: separate; overflow: hidden;">
    @foreach($details as $i => $detail)
        <tr style="background-color: {{ $i % 2 === 0 ? '#F9FAFB' : '#FFFFFF' }};">
            <td style="padding: 10px 16px; font-size: 14px; line-height: 1.5; color: #6B7280; font-weight: 600; border-bottom: {{ $loop->last ? 'none' : '1px solid #E5E7EB' }}; width: 40%; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
                {{ $detail['label'] }}
            </td>
            <td style="padding: 10px 16px; font-size: 14px; line-height: 1.5; color: #111827; text-align: right; border-bottom: {{ $loop->last ? 'none' : '1px solid #E5E7EB' }}; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
                {!! $detail['value'] !!}
            </td>
        </tr>
    @endforeach
</table>
