document.getElementById('leadForm').addEventListener('submit', function (e) {
  e.preventDefault();
  document.getElementById('msg').innerText =
    'Thank you! Check your email — your free guide is on its way.';
  this.reset();
});
