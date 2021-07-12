    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>TYPE</th>
                    <th>ADDRESS</th>
                    <th class="col-md-3">ASSIGNED STORE</th>
                </tr>
            </thead>
            <tbody>
            @foreach($address as $i)
                <tr>
                    <td>{{ $i['type'] }}</td>
                    <td>{{ $i['address'].', '. $i['city'].', '.$i['province_name'].$i['landmark'] }}</td>
                    <td>{{ $i['store_name'] }}</td>
                </tr>
            @endforeach
            <input type="hidden" id="email" value="{{ $email }}">
            </tbody>
        </table>
    </div>