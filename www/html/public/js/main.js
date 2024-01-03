$("#sendQuestionForm").bind("submit", function (e) {
    e.preventDefault();
    var url = $("#sendQuestionForm").attr("action");
    $.ajax({
        url: url,
        type: "post",
        data: { name: $("#contactFormName").val(), email: $("#contactFormEmail").val(), question: $("#contactFormQuestion").val() },
        beforeSend: function () {
            $("#formStatus").removeClass("invisible");
            $("#formStatus").addClass("visible");
        },
        success: function (data) {
            $("#formStatus").removeClass("visible");
            $("#formStatus").addClass("invisible");
            $("#contactFormMessage").removeClass("alert-danger");
            $("#contactFormMessage").addClass("alert-success");
            if(data == 'true') {
              $("#contactFormMessage").text("Спасибо! Мы постараемся ответить как можно скорее!");
              $("#sendQuestionForm").trigger('reset');
            } else {
              $("#contactFormMessage").addClass("alert-danger");
              $("#contactFormMessage").removeClass("alert-success");
              $("#contactFormMessage").text("Произошла ошибка... Повторите попытку позднее");
            }
        },
        error: function (error) {
          $("#formStatus").removeClass("visible");
          $("#contactFormMessage").addClass("alert-danger");
          $("#contactFormMessage").removeClass("alert-success");
          $("#formStatus").addClass("invisible");
          $("#contactFormMessage").text("Произошла ошибка... Повторите попытку позднее");
        },
    });
});

$("#adminFormLogin").bind("submit", function (e) {
    e.preventDefault();
    var url = $("#adminFormLogin").attr("action");
    $.ajax({
        url: url,
        type: "post",
        data: { login: $("#adminFormLoginLogin").val(), password: $("#adminFormLoginPassword").val()},
        beforeSend: function () {
            $("#formStatus").removeClass("invisible");
            $("#formStatus").addClass("visible");
        },
        success: function (data) {
          $("#formStatus").removeClass("visible");
          $("#formStatus").addClass("invisible");
          if(data == 'no_users') {
            $("#adminFormLoginMessage").addClass("alert-danger");
            $("#adminFormLoginMessage").removeClass("alert-success");
            $("#adminFormLoginMessage").text("Пользователь не найден!");
          } else {
            if(data == 'password_error') {
              $("#adminFormLoginMessage").addClass("alert-danger");
              $("#adminFormLoginMessage").removeClass("alert-success");
              $("#adminFormLoginMessage").text("Неверный пароль!");
            } else {
              if(data == 'not_admin') {
                $("#adminFormLoginMessage").addClass("alert-danger");
                $("#adminFormLoginMessage").removeClass("alert-success");
                $("#adminFormLoginMessage").text("Нет прав доступа!");
              } else {
                window.location.href = "/admin";
              }
            }
          }
        },
        error: function (error) {
          $("#formStatus").removeClass("visible");
          $("#adminFormLoginMessage").removeClass("alert-success");
          $("#adminFormLoginMessage").addClass("alert-danger");
          $("#formStatus").addClass("invisible");
          $("#adminFormLoginMessage").text("Произошла ошибка... Повторите попытку позднее");
        },
    });
});

$("#adminExitButton").bind("click", function (e) {
    e.preventDefault();
    if(confirm('Действительно выйти?')) {
        window.location.href = "/admin/destroy";
    }
});

$("#changePasswordForm").bind("submit", function (e) {
    e.preventDefault();
    var password1 = $("#newPassword1").val();
    var password2 = $("#newPassword2").val();
    if(password1 != password2) {
      alert('Пароли не совпадают!');
    } else {
      var url = $("#changePasswordForm").attr("action");
      $.ajax({
        url: url,
        type: "post",
        data: { password: password1 },
        success: function (data) {
          if(data == 'true') {
            alert('Пароль успешно изменён!');
            $("#changePasswordForm").trigger('reset');
          } else {
            alert('Произошла ошибка!');
          }
        },
        error: function (error) {
          alert('Произошла ошибка!');
        },
      });
    }
});

$("#addFaqGroupForm").bind("submit", function (e) {
    e.preventDefault();
    var url = $("#addFaqGroupForm").attr("action");
    $.ajax({
        url: url,
        type: "post",
        data: { header: $("#faqGroupFormHeader").val(), hidden: $("#faqGroupFormHidden").prop('checked')},
        success: function (data) {
          if(data == 'true') {
            window.location.href = "/admin";
          } else {
            alert("Произошла ошибка... Повторите попытку позднее");
          }
        },
        error: function (error) {
          alert("Произошла ошибка... Повторите попытку позднее");
        },
    });
});

$(".removeFaqGroupBtn").on('click', function (e) {
  e.preventDefault();
  var id = this.id;
  id = id.substr(17);
  if(confirm('Действительно удалить?')) {
    $.ajax({
      url: '/admin/removeFaqGroup',
      type: "post",
      data: { id: id },
      success: function (data) {
        if(data == 'true') {
          window.location.href = "/admin";
        } else {
          alert('Произошла ошибка!');
        }
      },
      error: function (error) {
        alert('Произошла ошибка!');
      },
    });
  }
});

$(".editFaqGroupBtn").on('click', function (e) {
  e.preventDefault();
  var id = this.id;
  id = id.substr(15);
  $.ajax({
    url: '/admin/startEditFaqGroup',
    type: "post",
    data: { id: id },
    dataType : 'json',
    success: function (data) {
      $("#editFaqFroupFormId").text(data.id);
      $("#editFaqGroupFormHeader").val(data.title);
      $("#editFaqGroupFormPosition").val(data.position);

      if(data.hidden == 1) {
        $('#editFaqGroupFormHidden').prop('checked', true);
      }
    },
    error: function (error) {
      alert('Произошла ошибка!');
    },
  });
});

$("#editFaqGroupForm").bind('submit', function (e) {
  e.preventDefault();
  $.ajax({
    url: '/admin/editFaqGroup',
    type: "post",
    data: { id: $("#editFaqFroupFormId").text(), title: $("#editFaqGroupFormHeader").val(),
      position: $("#editFaqGroupFormPosition").val(), hidden: $("#editFaqGroupFormHidden").prop('checked')},
    success: function (data) {
      if(data == 'true') {
        window.location.href = "/admin";
      } else {
        alert('Произошла ошибка!');
      }
    },
    error: function (error) {
      alert('Произошла ошибка!');
    },
  });
});

function setFooterHeight() {
    if ($(document).height() <= $(window).height()) $("footer").addClass("fixed-bottom");
    else $("footer").removeClass("fixed-bottom");
}

function setMapHeight() {
    var width = $("#mapHeight").width();
    $("#mapHeight").height(width);
    width = $("#chargingMap").width();
    $("#chargingMap").height(width);
}

$(window).resize(function () {
    setFooterHeight();
    setMapHeight();
});

$(document).ready(function () {
    setFooterHeight();
    setMapHeight();
});

$(document).mousemove(function (e) {
  setFooterHeight();
});

$("#addFaqForm").bind("submit", function (e) {
  e.preventDefault();
  var url = $("#addFaqForm").attr("action");
  $.ajax({
      url: url,
      type: "post",
      data: { header: $("#faqFormHeader").val(), answer: $("#faqFormAnswer").val(),
        hidden: $("#faqFormHidden").prop('checked'),group: $("#addFaqFormGroup").val()},
      success: function (data) {
        if(data == 'true') {
          window.location.href = "/admin";
        } else {
          alert("Произошла ошибка... Повторите попытку позднее");
        }
      },
      error: function (error) {
        alert("Произошла ошибка... Повторите попытку позднее");
      },
  });
});

$(".removeFaqBtn").on('click', function (e) {
  e.preventDefault();
  var id = this.id;
  id = id.substr(12);
  if(confirm('Действительно удалить?')) {
    $.ajax({
      url: '/admin/removeFaq',
      type: "post",
      data: { id: id },
      success: function (data) {
        if(data == 'true') {
          window.location.href = "/admin";
        } else {
          alert('Произошла ошибка!');
        }
      },
      error: function (error) {
        alert('Произошла ошибка!');
      },
    });
  }
});

$(".editFaqBtn").on('click', function (e) {
  e.preventDefault();
  var id = this.id;
  id = id.substr(10);
  $.ajax({
    url: '/admin/startEditFaq',
    type: "post",
    data: { id: id },
    dataType : 'json',
    success: function (data) {
      $("#editFaqFormId").text(data.id);
      $("#editFaqFormHeader").val(data.title);
      $("#editFaqFormPosition").val(data.position);
      $("#editFaqFormGroup").val(data.group);
      if(data.hidden == 1) {
        $('#editFaqFormHidden').prop('checked', true);
      }
      for(var i=0; i<data.positions_count; i++) {
        $("#editFaqFormPosition").append("<option>" + i + "</option>");
      }
    },
    error: function (error) {
      alert('Произошла ошибка!');
    },
  });
});

$("#editFaqForm").bind('submit', function (e) {
  e.preventDefault();
  $.ajax({
    url: '/admin/editFaq',
    type: "post",
    data: { id: $("#editFaqFormId").text(), title: $("#editFaqFormHeader").val(),
      position: $("#editFaqFormPosition").val(), hidden: $("#editFaqFormHidden").prop('checked'),
      group: $("#editFaqFormGroup").val()},
    success: function (data) {
      if(data == 'true') {
        window.location.href = "/admin";
      } else {
        alert('Произошла ошибка!');
      }
    },
    error: function (error) {
      alert('Произошла ошибка!');
    },
  });
});

$("#addDepartmentForm").bind("submit", function (e) {
    e.preventDefault();
    var url = $("#addDepartmentForm").attr("action");
    $.ajax({
        url: url,
        type: "post",
        data: { name: $("#departmentFormName").val(), hidden: $("#departmentFormHidden").prop('checked')},
        success: function (data) {
          if(data == 'true') {
            window.location.href = "/admin";
          } else {
            alert("Произошла ошибка... Повторите попытку позднее");
          }
        },
        error: function (error) {
          alert("Произошла ошибка... Повторите попытку позднее");
        },
    });
});

$(".removeDepartmentBtn").on('click', function (e) {
  e.preventDefault();
  var id = this.id;
  id = id.substr(19);
  if(confirm('Действительно удалить?')) {
    $.ajax({
      url: '/admin/removeDepartment',
      type: "post",
      data: { id: id },
      success: function (data) {
        if(data == 'true') {
          window.location.href = "/admin";
        } else {
          alert('Произошла ошибка!');
        }
      },
      error: function (error) {
        alert('Произошла ошибка!');
      },
    });
  }
});

$(".editDepartmentBtn").on('click', function (e) {
  e.preventDefault();
  var id = this.id;
  id = id.substr(17);
  $.ajax({
    url: '/admin/startEditDepartment',
    type: "post",
    data: { id: id },
    dataType : 'json',
    success: function (data) {
      $("#editDepartmentFormId").text(data.id);
      $("#editDepartmentFormName").val(data.name);
      $("#editDepartmentFormPosition").val(data.position);
      if(data.hidden == 1) {
        $('#editDepartmentFormHidden').prop('checked', true);
      }
    },
    error: function (error) {
      alert('Произошла ошибка!');
    },
  });
});

$("#editDepartmentForm").bind('submit', function (e) {
  e.preventDefault();
  $.ajax({
    url: '/admin/editDepartment',
    type: "post",
    data: { id: $("#editDepartmentFormId").text(), name: $("#editDepartmentFormName").val(),
      position: $("#editDepartmentFormPosition").val(), hidden: $("#editDepartmentFormHidden").prop('checked')},
    success: function (data) {
      if(data == 'true') {
        window.location.href = "/admin";
      } else {
        alert('Произошла ошибка!');
      }
    },
    error: function (error) {
      alert('Произошла ошибка!');
    },
  });
});

$("#addEmployeeForm").bind("submit", function (e) {
  e.preventDefault();
  var url = $("#addEmployeeForm").attr("action");
  var that = $(this);
  var formData = new FormData(that.get(0));
  $.ajax({
      url: url,
      type: "post",
      contentType: false,
			processData: false,
      data: formData,
      dataType : 'json',
      success: function (data) {
        if(data == true) {
          window.location.href = "/admin";
        } else {
          alert("Произошла ошибка... Повторите попытку позднее");
        }
      },
      error: function (error) {
        alert("Произошла ошибка... Повторите попытку позднее");
      },
  });
});

$("#removeFile").on('click', function (e) {
  e.preventDefault();
  $("#employeeFormPhoto").val('');
});

$(".removeEmployeeBtn").on('click', function (e) {
  e.preventDefault();
  var id = this.id;
  id = id.substr(17);
  if(confirm('Действительно удалить?')) {
    $.ajax({
      url: '/admin/removeEmployee',
      type: "post",
      data: { id: id },
      success: function (data) {
        if(data == 'true') {
          window.location.href = "/admin";
        } else {
          alert('Произошла ошибка!');
        }
      },
      error: function (error) {
        alert('Произошла ошибка!');
      },
    });
  }
});

$(".editEmployeeBtn").on('click', function (e) {
  e.preventDefault();
  var id = this.id;
  id = id.substr(15);
  $.ajax({
    url: '/admin/startEditEmployee',
    type: "post",
    data: { id: id },
    dataType : 'json',
    success: function (data) {
      $("#editEmployeeFormId").val(data.id);
      $("#editEmployeeFormName").val(data.name);
      $("#editEmployeeFormPosition").val(data.position);
      $("#editEmployeeFormNewPhoto").val('');
      $("#editEmployeeFormDepartment").val(data.department);
      $("#editEmployeeFormPost").val(data.post);
      $("#oldEmployeePhoto").empty();
      $("#oldEmployeePhoto").append("<img class='employeePhotos_admin' src='../"+ data.photo +"'></img>");
      if(data.hidden == 1) {
        $('#editEmployeeFormHidden').prop('checked', true);
      }
      for(var i=0; i<data.positions_count; i++) {
        $("#editEmployeeFormPosition").append("<option>" + i + "</option>");
      }
    },
    error: function (error) {
      alert('Произошла ошибка!');
    },
  });
});

$('#editEmployeeFormWithoutPhoto').change(function() {
   if($(this).is(":checked")) {
     $("#editEmployeeFormNewPhoto").prop('disabled', true);
     $("#editEmployeeFormNewPhoto").val('');
   } else {
     $("#editEmployeeFormNewPhoto").prop('disabled', false);
     $("#editEmployeeFormNewPhoto").val('');
   }
});

$("#editEmployeeForm").bind("submit", function (e) {
  e.preventDefault();
  var url = $("#editEmployeeForm").attr("action");
  var $that = $(this);
  var formData = new FormData($that.get(0));
  $.ajax({
      url: url,
      type: "post",
      contentType: false,
			processData: false,
      data: formData,
      dataType : 'json',
      success: function (data) {
        if(data == true) {
          window.location.href = "/admin";
        } else {
          alert("Произошла ошибка... Повторите попытку позднее");
        }
      },
      error: function (error) {
        alert("Произошла ошибка... Повторите попытку позднее");
      },
  });
});

$("#removeUnnecessaryEmployeePhotos").bind("click", function (e) {
    e.preventDefault();
    $.ajax({
        url: "/admin/removeUnnecessaryEmployeePhotos",
        type: "post",
        success: function (data) {
          alert('Удалено файлов: ' + data);
        },
        error: function (error) {
          alert("Произошла ошибка... Повторите попытку позднее");
        },
    });
});

$("#addMirrorForm").bind("submit", function (e) {
  e.preventDefault();
  var url = $("#addMirrorForm").attr("action");
  var $that = $(this);
  $("#mirrorDescription").val(CKEDITOR.instances.mirrorDescription.getData());
  var formData = new FormData($that.get(0));
  $.ajax({
      url: url,
      type: "post",
      contentType: false,
			processData: false,
      data: formData,
      dataType : 'json',
      success: function (data) {
        if(data == true) {
          window.location.href = "/admin";
        } else {
          alert("Произошла ошибка... Повторите попытку позднее");
        }
      },
      error: function (error) {
        alert("Произошла ошибка... Повторите попытку позднее");
      },
  });
});

$(".removeMirrorBtn").on('click', function (e) {
  e.preventDefault();
  var id = this.id;
  id = id.substr(15);
  if(confirm('Действительно удалить?')) {
    $.ajax({
      url: '/admin/removeMirror',
      type: "post",
      data: { id: id },
      success: function (data) {
        if(data == 'true') {
          window.location.href = "/admin";
        } else {
          alert('Произошла ошибка!');
        }
      },
      error: function (error) {
        alert('Произошла ошибка!');
      },
    });
  }
});

$(".editMirrorBtn").on('click', function (e) {
  e.preventDefault();
  var id = this.id;
  id = id.substr(13);
  $.ajax({
    url: '/admin/startEditMirror',
    type: "post",
    data: { id: id },
    dataType : 'json',
    success: function (data) {
      $("#editMirrorFormId").val(data.id);
      $("#editMirrorFormHeader").val(data.title);
      $("#editMirrorFormPrice").val(data.price);
      $("#editMirrorFormType").val(data.type);
      CKEDITOR.instances.editmirrorDescription.setData(data.description);
      $("#oldProductPhoto").empty();
      $("#oldProductPhoto").append("<img class='employeePhotos_admin' src='../"+ data.photo +"'></img>");
    },
    error: function (error) {
      alert('Произошла ошибка!');
    },
  });
});

$("#editMirrorForm").bind("submit", function (e) {
  e.preventDefault();
  var url = $("#editMirrorForm").attr("action");
  $("#editmirrorDescription").val(CKEDITOR.instances.editmirrorDescription.getData());
  var $that = $(this);
  var formData = new FormData($that.get(0));
  $.ajax({
      url: url,
      type: "post",
      contentType: false,
			processData: false,
      data: formData,
      dataType : 'json',
      success: function (data) {
        if(data == true) {
          window.location.href = "/admin";
        } else {
          alert("Произошла ошибка... Повторите попытку позднее");
        }
      },
      error: function (error) {
        alert("Произошла ошибка... Повторите попытку позднее");
      },
  });
});

$.fn.modal.Constructor.prototype._enforceFocus  = function() {
  modal_this = this
  $(document).on('focusin.modal', function (e) {
    if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
    && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select')
    && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
      modal_this.$element.focus()
    }
  })
};

$(".addToCartBtn").bind("click", function (e) {
    e.preventDefault();
    var id = this.id;
    id = id.substr(12);
    $.ajax({
      url: '/index/addToCart',
      type: "post",
      data: { id: id },
      success: function (data) {
        $(".addToCartBtn").attr("disabled", 'true');
        $(".addToCartBtn").text("В корзине");
      },
      error: function (error) {
        alert('Произошла ошибка!');
      },
    });
});

$(".removeFromCart").bind("click", function (e) {
  if(confirm('Действительно удалить?')) {
    e.preventDefault();
    var id = this.id;
    id = id.substr(14);
    $.ajax({
      url: '/index/removeFromCart',
      type: "post",
      data: { id: id },
      success: function (data) {
      window.location.href = "/index/cart";
      },
      error: function (error) {
        alert('Произошла ошибка!');
      },
    });
  }
});

$( ".changeCartCount" ).change(function () {
  var id = this.id;
  id = id.substr(15);
  $.ajax({
    url: '/index/changeCartCount',
    type: "post",
    data: { id: id, count: $("#"+this.id).val() },
    success: function (data) {
      $("#cartPrice").empty();
      $("#cartPrice").text(data + " рублей");
    },
    error: function (error) {
      alert('Произошла ошибка!');
    },
  });
});

$( "#placeOrder" ).click(function () {
  window.location.href = "/index/order";
});

$("#processOrder").bind("submit", function (e) {
    e.preventDefault();
    var url = $("#processOrder").attr("action");
    $.ajax({
        url: url,
        type: "post",
        data: { email: $("#orderEmail").val(), name: $("#orderName").val(), surname: $("#orderSurname").val(), address:  $("#orderAddress").val(),
         comment:  $("#orderComment").val(), sum:  $("#orderSum").val()},
        success: function (data) {
            if(data == 'true') {
              window.location.href = "/index/pay";
            } else {
              alert('Произошла ошибка!');
            }
        },
        error: function (error) {
          alert('Произошла ошибка!');
        },
    });
});

$(".removeOrderBtn").on('click', function (e) {
  e.preventDefault();
  var id = this.id;
  id = id.substr(14);
  if(confirm('Действительно удалить?')) {
    $.ajax({
      url: '/admin/removeOrder',
      type: "post",
      data: { id: id },
      success: function (data) {
        if(data == 'true') {
          window.location.href = "/admin";
        } else {
          alert('Произошла ошибка!');
        }
      },
      error: function (error) {
        alert('Произошла ошибка!');
      },
    });
  }
});
