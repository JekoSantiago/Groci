    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Promo Price</th>
                    <th class="text-center">Effective Date From</th>
                    <th class="text-center">Effective Date To</th>
                    <th class="text-center">Promo</th>
                </tr>
            </thead>
            <tbody>
            @foreach($itemPrice as $i)
                <tr>
                    <td class="text-center"><input type="radio" {{ ($i->edate_to == NULL) ? 'checked=checked' : '' }} disabled></td>
                    <td class="text-center">{{ number_format($i->price, 2, '.', '') }}</td>
                    <td class="text-center">{{ number_format($i->promo_price, 2, '.', '') }}</td>
                    <td class="text-center">{{ $i->edate_from }}</td>
                    <td class="text-center">{{ ($i->edate_to == NULL) ? 'PRESENT' : $i->edate_to }}</td>
                    <td class="text-center"><span class="label {{ ($i->is_promo == 0) ? 'bg-danger-800' : 'bg-success-800' }}">{{ ($i->is_promo == 0) ? 'NO' : 'YES' }}</span></td>
                </tr>
            @endforeach
            <input type="hidden" id="itemID" value="{{ $itemID }}">
            </tbody>
        </table>
    </div>