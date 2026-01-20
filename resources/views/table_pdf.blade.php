<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Table Export</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #444;
      padding: 4px;
      text-align: center;
    }
  </style>
</head>

<body>
  <div style="text-align: center;">
    <h2>Statement of Account: </h2>
  </div>
  <table>
    <thead>
      <tr>
        <th>s/l</th>
        <th>ICRIS</th>
        <th>Invoice type</th>
        <th>Invoice period</th>
        <th>Invoice date</th>
        <th>Due date</th>
        <th>Invoice amount</th>
        <th>Settled amount</th>
        <th>Due amount</th>
        <th>Status</th>

        <!-- Add your table columns -->
      </tr>
    </thead>
    <tbody>
	  @php $i=1; @endphp
      @foreach($records as $record)
    
      <tr>
        <td>{{ $i++ }}</td>
        <td>{{ $record->icris }}</td>
        <td>{{ $record->invoiceType }}</td>
        <td>{{ $record->invoicePeriod }}</td>
        <td>{{ $record->invoiceDate }}</td>
        <td>{{ $record->dueDate }}</td>
        <td style="text-align: right!important;">{{ number_format($record->invoiceAmount, 2) }}</td>
        <td style="text-align: right!important;">{{ number_format($record->settledAmount, 2) }}</td>
        <td style="text-align: right!important;">{{ number_format($record->dueAmount, 2) }}</td>
        <td style="color: {{
          $record->status == 'Over Due' ? 'red' :
          ($record->status == 'Due' ? 'gold' : 'green')
      }}">
          {{ $record->status }}
        </td>

        <!-- Add other fields -->
      </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>