<h1>Timesheets</h1>
{{-- <table>
    <thead>
        <th>Calendario</th>
        <th>Tipo</th>
        <th>Entrada</th>
        <th>Salida</th>
    </thead>
    <tbody>
        @foreach ($timesheets as $item )
          <tr>
            <td>{{ $item->calendar->name }}</td>
            <td>{{ $item->type }}</td>
            <td>{{ $item->day_in }}</td>
            <td>{{ $item->day_out }}</td>
          
        </tr>
            
        @endforeach
      
    </tbody>
</table> --}}

<table class="table-auto">
  <thead>
    <tr>
      <th>Song</th>
      <th>Artist</th>
      <th>Year</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>The Sliding Mr. Bones (Next Stop, Pottersville)</td>
      <td>Malcolm Lockyer</td>
      <td>1961</td>
    </tr>
    <tr>
      <td>Witchy Woman</td>
      <td>The Eagles</td>
      <td>1972</td>
    </tr>
    <tr>
      <td>Shining Star</td>
      <td>Earth, Wind, and Fire</td>
      <td>1975</td>
    </tr>
  </tbody>
</table>