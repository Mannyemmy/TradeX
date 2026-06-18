{{-- Exchange/Swap Script — included by asset.blade.php --}}
<script>
(function() {
    const destinationAsset = document.getElementById('destinationasset');
    const sourceAsset = document.getElementById('sourceasset');
    const amountInput = document.getElementById('amount');
    const quantityDisplay = document.getElementById('quantity');
    const realQuantity = document.getElementById('realquantity');
    const exchangeForm = document.getElementById('exchnageform');

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            background: '#161A1E',
            color: '#E8EAED',
            confirmButtonColor: '#2E5C8A'
        });
    }

    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
            background: '#161A1E',
            color: '#E8EAED',
            confirmButtonColor: '#2E5C8A'
        });
    }

    function validate() {
        amountInput.value = '';
        quantityDisplay.value = '';
        if (destinationAsset.value === sourceAsset.value) {
            showError('Source and Destination account cannot be the same');
            destinationAsset.value = '';
            amountInput.placeholder = '';
            quantityDisplay.placeholder = '';
        } else {
            amountInput.placeholder = `Enter amount of ${sourceAsset.value.toUpperCase()}`;
            quantityDisplay.placeholder = `Quantity of ${destinationAsset.value.toUpperCase()}`;
        }
    }

    // Initial validation
    if (destinationAsset.value === sourceAsset.value) {
        showError('Source and Destination account cannot be the same');
        destinationAsset.value = '';
        amountInput.placeholder = '';
        quantityDisplay.placeholder = '';
        amountInput.value = '';
        quantityDisplay.value = '';
    } else {
        amountInput.placeholder = `Enter amount of ${sourceAsset.value.toUpperCase()}`;
        quantityDisplay.placeholder = `Quantity of ${destinationAsset.value.toUpperCase()}`;
    }

    destinationAsset.addEventListener('change', validate);
    sourceAsset.addEventListener('change', validate);

    // Fetch conversion quantity on amount input
    let debounceTimer;
    amountInput.addEventListener('keyup', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            if (!amountInput.value) return;
            const url = "{{ url('/dashboard/asset-price/') }}" + '/' + sourceAsset.value + '/' + destinationAsset.value + '/' + amountInput.value;
            fetch(url, {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 200) {
                    quantityDisplay.value = data.data + ' ' + destinationAsset.value.toUpperCase();
                    realQuantity.value = data.data;
                }
            })
            .catch(err => console.error(err));
        }, 300);
    });

    // Handle form submission
    exchangeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!amountInput.value) {
            showError('Please enter an amount to exchange');
            return;
        }

        const formData = new FormData(exchangeForm);
        fetch("{{ route('exchangenow') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                showSuccess(data.success);
                setTimeout(() => window.location.reload(true), 3000);
            } else {
                showError(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            showError('An unexpected error occurred.');
        });
    });

    // Fetch USD equivalents for crypto balances
    function getCurrBalance() {
        document.querySelectorAll('.usdelement').forEach(el => {
            const coin = el.id;
            fetch("{{ url('dashboard/balances/') }}" + '/' + coin, {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                el.textContent = "@userCurrency" + data.data;
            })
            .catch(err => console.error(err));
        });
    }

    getCurrBalance();
    setInterval(getCurrBalance, 60000);
})();
</script>
