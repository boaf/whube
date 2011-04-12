$("#username").change(function () {
      var str = "";
      var tzo = ( new Date() . gettimezoneoffset() / 60 ) * ( -1 );
      $("#tzv").text(str);
    })
    .change();

