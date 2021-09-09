/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
//var basePath = '/projects/directoryapp/public';
var basePath = '';

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
	//CKEDITOR.config.allowedContent = true;
	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup', 'Underline' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'NumberedList', 'BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	config.fillEmptyBlocks = false;
	config.tabSpaces = 0;
	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
	config.allowedContent = true;
	//config.filebrowserBrowseUrl = "/editor-upload";

    config.filebrowserBrowseUrl = basePath + '/plugins/ckeditor/plugins/ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl = basePath + '/plugins/ckeditor/plugins/ckfinder/ckfinder.html?type=Images';
    config.filebrowserFlashBrowseUrl = basePath + '/plugins/ckeditor/plugins/ckfinder/ckfinder.html?type=Flash';
    config.filebrowserUploadUrl = basePath + '/plugins/ckeditor/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserUploadUrl = basePath + '/plugins/ckeditor/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl = basePath + '/plugins/ckeditor/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl = basePath + '/plugins/ckeditor/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'

};

CKEDITOR.dtd.$removeEmpty['span'] = false; //allow empty span tag
CKEDITOR.dtd.$removeEmpty['i'] = false; //allow empty i tag