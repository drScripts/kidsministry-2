$(document).ready(function () {
  $("#quiz-field").hide();
  $("#quiz-select").removeAttr("required").attr("disabled", true);

  $("#zoom-field").hide();
  $("#zoom-select").removeAttr("required").attr("disabled", true);

  $("#komsel-field").hide();
  $("#komsel-select").removeAttr("required").attr("disabled", true);

  $("#aba-field").hide();
  $('input[name="aba"]').attr("disabled", true);

  $("#img-field").hide();
  $("#vid-field").hide();
  $("#submit-buttons").hide();
  $("#pembimbing-select").on("change", function () {
    $("#quiz-field").hide();
    $("#quiz-select").removeAttr("required").attr("disabled", true);

    $("#zoom-field").hide();
    $("#zoom-select").removeAttr("required").attr("disabled", true);

    $("#komsel-field").hide();
    $("#komsel-select").removeAttr("required").attr("disabled", true);

    $("#aba-field").hide();
    $('input[name="aba"]').attr("disabled", true);

    $("#img-field").hide();
    $("#vid-field").hide();
    $("#submit-buttons").hide();
  });

  $("#children-select").on("change", function () {
    $("#quiz-field").hide();
    $("#quiz-select").removeAttr("required").attr("disabled", true);

    $("#zoom-field").hide();
    $("#zoom-select").removeAttr("required").attr("disabled", true);

    $("#komsel-field").hide();
    $("#komsel-select").removeAttr("required").attr("disabled", true);

    $("#aba-field").hide();
    $('input[name="aba"]').attr("disabled", true);

    $("#img-field").hide();
    $("#vid-field").hide();

    $("#submit-buttons").hide();

    var option = $("option:selected", this).attr("kelas");
    if (!option) {
      $("#quiz-field").hide();
      $("#quiz-select").removeAttr("required").attr("disabled", true);

      $("#zoom-field").hide();
      $("#zoom-select").removeAttr("required").attr("disabled", true);

      $("#komsel-field").hide();
      $("#komsel-select").removeAttr("required").attr("disabled", true);

      $("#aba-field").hide();
      $('input[name="aba"]').attr("disabled", true);

      $("#img-field").hide();
      $("#vid-field").hide();
      $("#submit-buttons").hide();
    } else if (option == "Teens") {
      $("#quiz-field").show();
      $("#quiz-select").removeAttr("disabled").attr("required", true);

      $("#zoom-field").show();
      $("#zoom-select").removeAttr("disabled").attr("required", true);

      //   $("#komsel-field").hide();
      //   $("#komsel-select").removeAttr("required").attr("disabled", true);

      $("#aba-field").show();
      $('input[name="aba"]').removeAttr("disabled");

      $("#submit-buttons").show();
    } else {
      $("#quiz-field").show();
      $("#quiz-select").removeAttr("disabled").attr("required", true);

      $("#zoom-field").show();
      $("#zoom-select").removeAttr("disabled").attr("required", true);

      $("#img-field").show();
      $("#vid-field").show();

      $("#submit-buttons").show();
    }
  });
});
