<script>
  $(document).ready(function() {
    $('#product_id').change(function() {
      var province = $(this).val();
      var path = "{{URL::route('backend.product.getFavourite')}}";
      $.ajax({
        url:path,
        data: {'product_id':province,'_token':$('meta[name="csrf-token"]').attr('content')},
        method:'post',
        dataType : 'text',
        success:function(response) {
            $('#favourite_id').empty();
          $('#favourite_id').append(response);

        }
      });
    })
  });
</script>