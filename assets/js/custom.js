(function ($) {
  $('#product-category').change(function () {
    $.ajax({
      url: pg.ajaxurl,
      method: 'POST',
      data: {
        action: 'pgFilterProducts',
        category: $(this).find(':selected').val(),
      },
      beforeSend: function () {
        $('#result-products').html('Cargando ...');
      },
      success: function (data) {
        let html = '';
        data.forEach((item) => {
          html += `
            <div class="col-4">
              <figure>
                ${item.image}
              </figure>
              <h4 class="my-3 text-center">
                <a href="${item.link}">${item.title}</a>
              </h4>
            </div>
          `;
        });
        $('#result-products').html(html);
      },
      error: function (error) {
        console.error(error);
      },
    });
  });

  $(document).ready(function () {
    $.ajax({
      url: pg.apiurl + '/novedades/3',
      method: 'GET',
      beforeSend: function () {
        $('#result-novedades').html('Cargando ...');
      },
      success: function (data) {
        let html = '';
        data.forEach((item) => {
          html += `
            <div class="col-4">
              <figure>
                ${item.image}
              </figure>
              <h4 class="my-3 text-center">
                <a href="${item.link}">${item.title}</a>
              </h4>
            </div>
          `;
        });
        $('#result-novedades').html(html);
      },
      error: function (error) {
        console.error(error);
      },
    });
  });
})(jQuery);
