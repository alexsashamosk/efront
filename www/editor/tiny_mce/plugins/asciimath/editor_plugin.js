function getCaretPosition() {
	sel = window.getSelection();
	if (sel.rangeCount) {
		range = sel.getRangeAt(0);
		// console.log(range.commonAncestorContainer.parentNode);
		caretElement = range.commonAncestorContainer.parentNode;
		caretPosBegin = range.startOffset;
		caretPosEnd = range.endOffset;
	}
}

function update_formuls(){
	$("#latex_img").attr('src', latex_src + $("#mytextarea").val());
}

var caretPosBegin = 0;
var caretPosEnd = 0;
var latex_src = 'http://latex.codecogs.com/gif.latex?';

jQuery(document).ready(function() {
	FormElements.init();
	var cursor_position = 0;
	$("#choose_file").change(function() {
		$("#chosen_file").val($(this).val());
		$(this).parents('form').submit();
	});

	$("#open_latex_editor").click(function () {
		$("#latex_editor").modal("show");
	});

	$(".note-editor").on('click', '.btn-group', function function_name () {
		if (!$(this).hasClass('open')) {
			$(this).addClass('open');
		} else {
			$(this).removeClass('open');
		};
	})

	// Изменение LaTeX кода в редакторе через нажатие кнопок с шаблонами
	$("#latex_editor").on('click', '.tabbable .math', function() {
		var tex_text = $(this).parents('.tex-text').children('script').text();
		var element_text = $("#mytextarea").val();
		var text_to = $("#mytextarea").prop("selectionStart");
		var text_from = $("#mytextarea").prop("selectionEnd");
		var text_before = element_text.substr(0, text_to);
		var text_after = element_text.substr(text_from);
		$("#mytextarea").val(text_before+tex_text+text_after);
		$("#mytextarea").prop("selectionStart", text_before.length + tex_text.length);
		$("#mytextarea").prop("selectionEnd", text_before.length + tex_text.length);
		$("#mytextarea").focus();
		update_formuls();
	});

	var caretElement = $(".note-editable").first();


	$(".form-group").on('click', '.note-editable', function() {
		getCaretPosition();
	});

	// Открытие окна для вставки кода
	$("#open_embed_code").click(function() {
		$("#embed_code").modal('show');
	});

	// Нажитие кнопки вставки кода
	$("#insert_code").click(function(){
		var embed_text = $("#embed_code_text").val();
		if (embed_text.length!=0) {
			$("button[data-event='showLinkDialog']").first().click();
			$(".note-link-text").first().val(embed_text);
			setTimeout(function(){
				$(".note-link-btn").first().click();
			}, 0);
			$("#embed_code").modal('hide');
		};
	});

	$("button[data-event='fullscreen']").click(function() {
        $("#embed_code").appendTo("div.note-dialog");
        $("#latex_editor").appendTo("div.note-dialog");
	});

	$("#save_file").click(function() {
		$("#file_text").val($(".note-editable").first().html());
		if ($("#save_file_name").val()!='') {
			$(this).parent().submit();
		};
	});

	// Добавление формулы в редактор
	$("#add_tex_text").click(function() {
		$("#latex_editor").modal("hide");
		$("button[data-event='showImageDialog']").first().click();
		$(".note-image-url").first().val($("#latex_img").attr('src'));
		setTimeout(function(){
			$(".note-image-btn").first().click();
		}, 0);
		
	});

	$("#open_latex_editor").hover(
		function() {
			var button = $("#open_latex_editor");
			var position = button.position();
			$("#open_latex_editor_tooltip").css({
				left:position.left - ($("#open_latex_editor_tooltip").outerWidth() - $("#open_latex_editor").outerWidth())/2,
				top:$("#open_latex_editor").outerHeight()
			});
			$("#open_latex_editor_tooltip").show();
		},
		function() {
			$("#open_latex_editor_tooltip").hide();
		}
	);
	$("#open_latex_editor").parent().append('<div id="open_latex_editor_tooltip" class="tooltip fade bottom in" style="display: none;"><div class="tooltip-arrow"></div><div class="tooltip-inner">Insert formula</div></div>');
	
	$("#open_embed_code").hover(
		function() {
			var button = $("#open_embed_code");
			var position = button.position();
			$("#open_embed_code_tooltip").css({
				left:position.left - ($("#open_embed_code_tooltip").outerWidth() - $("#open_embed_code").outerWidth())/2,
				top:$("#open_embed_code").outerHeight()
			});
			$("#open_embed_code_tooltip").show();
		},
		function() {
			$("#open_embed_code_tooltip").hide();
		}
	);
	$("#open_embed_code").parent().append('<div id="open_embed_code_tooltip" class="tooltip fade bottom in" style="display: none;"><div class="tooltip-arrow"></div><div class="tooltip-inner">Embed video code</div></div>');
});
