<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contract</title>

<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: 'Poppins', sans-serif;
  font-size: 12px;
  color: #1a1a1a;
  padding: 40px;
  background: #fff;
}

.header { margin-bottom: 30px; position: relative; }
.header-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  padding-bottom: 15px;
  border-bottom: 2px solid #f0f0f0;
}
.main-title { font-size: 42px; font-weight: 300; letter-spacing: 8px; }
.estimate-number { font-size: 14px; color: #666; }

/* Top Section: From | Bill To | Client */
.grid-3 { display: table; width: 100%; margin-bottom: 30px; }
.grid-3 .info-card { display: table-cell; width: 33%; vertical-align: top; border: 1px solid #f0f0f0; padding: 15px; }
.card-label { font-size: 10px; color: #999; margin-bottom: 5px; text-transform: uppercase; }
.card-value { font-size: 14px; font-weight: 500; }
.card-sub { font-size: 11px; color: #666; margin-bottom: 2px; }

/* Dates Section */
.grid-4 { display: table; width: 100%; margin-bottom: 30px; }
.grid-4 .date-item { display: table-cell; width: 33%; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
.date-label { font-size: 9px; color: #999; }
.date-value { font-size: 12px; font-weight: 500; }

/* Products & Services Table */
.section-title-modern { font-size: 16px; margin: 25px 0 15px; border-bottom: 2px solid #f0f0f0; padding-bottom: 8px; }
.items-table-modern { width: 100%; border-collapse: collapse; font-size: 11px; margin-bottom: 15px; }
.items-table-modern th, .items-table-modern td { padding: 10px 8px; border-bottom: 1px solid #f0f0f0; }
.items-table-modern th { background: #fafafa; border-bottom: 2px solid #e0e0e0; }
.right { text-align: right; }
.center { text-align: center; }
.item-description { font-size: 9px; color: #666; font-style: italic; }

/* Summary Table */
.summary-modern { width: 100%; margin-top: 10px; }
.summary-modern table { width: 100%; border-collapse: collapse; font-size: 12px; }
.summary-modern td { padding: 5px; }
.summary-modern .label { text-align: right; width: 75%; font-weight: 500; }
.summary-modern .amount { text-align: right; width: 25%; font-weight: 500; }
.summary-modern .total-label { font-size: 14px; font-weight: 700; }
.summary-modern .total-amount { font-size: 14px; font-weight: 700; }

/* Footer */
.footer-modern { margin-top: 40px; text-align: center; font-size: 10px; color: #999; }

/* Watermark */
.watermark-number { position: fixed; bottom: 30px; right: 30px; font-size: 60px; color: #f5f5f5; opacity: 0.3; }
</style>
</head>
<body>

<div class="watermark-number">#{{ $contract->slug ?? 'N/A' }}</div>

<!-- Header -->
<div class="header">
  <div class="header-top">
    <div>
      <div class="main-title">CONTRACT</div>
      <div class="estimate-number">REF: {{ $contract->slug ?? 'N/A' }}</div>
    </div>
    <div>NEW</div>
  </div>
</div>

<!-- From / Bill To / Client -->
<div class="grid-3">
  <div class="info-card">
    <div class="card-label">From</div>
    <div class="card-value">{{ $company->name ?? 'N/A' }}</div>
    <div class="card-sub">{{ $company->address ?? 'N/A' }}</div>
    <div class="card-sub">{{ $company->city ?? 'N/A' }}, {{ $company->zip ?? 'N/A' }}, {{ $company->country ?? 'N/A' }}</div>
    <div class="card-sub">{{ $company->email ?? 'N/A' }}</div>
  </div>

  <div class="info-card">
    <div class="card-label">Bill To</div>
    <div class="card-value">{{ $organization->name ?? 'N/A' }}</div>
    <div class="card-sub">Department: {{ $organization->department ?? 'N/A' }}</div>
  </div>

  <div class="info-card">
    <div class="card-label">Client</div>
    <div class="card-value">{{ $client->name ?? 'N/A' }}</div>
  </div>
</div>

<!-- Dates -->
<div class="grid-4">
    <div class="date-item">
        <div class="date-label">Issue Date</div>
        <div class="date-value">
            {{ $estimate->issue_date ? $estimate->issue_date : 'N/A' }}
        </div>
    </div>
    <div class="date-item">
        <div class="date-label">Event Date</div>
        <div class="date-value">
            {{ $estimate->event_date ? $estimate->event_date : 'N/A' }}
        </div>
    </div>
    <div class="date-item">
        <div class="date-label">Valid Until</div>
        <div class="date-value">
            {{ $estimate->valid_until ? $estimate->valid_until : 'N/A' }}
        </div>
    </div>
</div>

<!-- Products & Services -->
<div class="section-title-modern">Products & Services</div>

<table class="items-table-modern">
<thead>
<tr>
  <th>Item</th>
  <th>Description</th>
  <th class="center">Qty</th>
  <th class="right">Unit Price</th>
  <th class="right">Amount</th>
</tr>
</thead>
<tbody>
@foreach($estimate->items as $item)
<tr>
  <td>{{ $item->name ?? 'N/A' }}</td>
  <td class="item-description">{{ $item->description ?? 'N/A' }}</td>
  <td class="center">{{ number_format($item->quantity ?? 0) }}</td>
  <td class="right">{{ number_format($item->price ?? 0, 2) }}</td>
  <td class="right">{{ number_format($item->total ?? 0, 2) }}</td>
</tr>
@endforeach
</tbody>
</table>

<!-- Summary -->
<div class="summary-modern">
  <table>
    <tr>
      <td class="label">Subtotal</td>
      <td class="amount">{{ number_format($estimate->subtotal ?? 0, 2) }}</td>
    </tr>
    <tr>
      <td class="label total-label">Total</td>
      <td class="amount total-amount">{{ number_format($estimate->total ?? 0, 2) }}</td>
    </tr>
  </table>
</div>

<!-- Signature -->
<div class="section-title-modern">Signature</div>
<!-- @if(!empty($contract->signature))
    <img src="data:image/png;base64,{{ $contract->signature }}" alt="Signature" style="width:150px; height:auto;">
@else
    <div>N/A</div>
@endif -->

<!-- Notes -->
<!-- Note Section -->
<div class="section-title-modern">Note</div>

@if(!empty($estimate->note))
    <div style="font-family: 'Open Sans', Arial, sans-serif; font-size:12px; color:#000; line-height:1.5;">
        {!! strip_tags($estimate->note, '<p><br><strong><em><ul><ol><li>') !!}
    </div>
@else
    <div>N/A</div>
@endif

<!-- Terms & Conditions Section -->
<div class="section-title-modern">Terms & Conditions</div>

@if(!empty($estimate->terms))
    <div style="font-family: 'Open Sans', Arial, sans-serif; font-size:12px; color:#000; line-height:1.5;">
        {!! strip_tags($estimate->terms, '<p><br><strong><em><ul><ol><li>') !!}
    </div>
@else
    <div>N/A</div>
@endif
<!-- Footer -->
<div class="footer-modern">
  CONTRACT #{{ $contract->slug ?? 'N/A' }} • GENERATED {{ optional($contract->created_at)->format('m/d/Y') ?? 'N/A' }}
</div>

</body>
</html>