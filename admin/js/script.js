$(document).ready(function () {
  $("#summernote").summernote({
    height: 100,
    toolbar: [
      ["style", ["bold", "italic", "underline", "clear"]],
      ["fontsize", ["fontsize"]],
      ["color", ["color"]],
    ],
    callbacks: {
      onChange: function (contents) {
        if (contents == "<p><br></p>") {
          var currentSummernoteInstance = $(this);
          currentSummernoteInstance.summernote("code", "");
        }
      },
    },
  });
});
