{{-- Payment method selection script (included on deposit pages) --}}
<script>
    let paymethod = document.querySelector('#paymethod');
    var lastchosen = document.getElementById('lastchosen');

    function checkpamethd(id) {
        let url = "{{ url('/dashboard/get-method/') }}" + '/' + id;
        fetch(url)
            .then(res => res.json())
            .then(response => {
                paymethod.value = response;
                var paymentchosed = id + 'customCheck1';
                var last = lastchosen.value + 'customCheck1';

                if (id === lastchosen.value) {
                    document.getElementById(paymentchosed).checked = true;
                    lastchosen.value = id;
                } else {
                    if (lastchosen.value == 0) {
                        document.getElementById(paymentchosed).checked = true;
                        lastchosen.value = id;
                    } else {
                        document.getElementById(last).checked = false;
                        document.getElementById(paymentchosed).checked = true;
                        lastchosen.value = id;
                    }
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Payment method: ' + response,
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#161A1E',
                    color: '#E8EAED'
                });
            })
            .catch(err => console.log(err));
    }

    document.getElementById('submitpaymentform')?.addEventListener('submit', function(e) {
        if (paymethod.value == "") {
            e.preventDefault();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Please choose a payment method',
                showConfirmButton: false,
                timer: 3000,
                background: '#161A1E',
                color: '#E8EAED'
            });
        } else {
            let makepayurl = "{{ url('/dashboard/newdeposit') }}";
            document.getElementById("submitpaymentform").action = makepayurl;
        }
    });
</script>
