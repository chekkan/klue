var dropbox = document.getElementById("dropbox");
 
// init event handlers
dropbox.addEventListener("dragenter", dragEnter, false);
dropbox.addEventListener("dragexit", dragExit, false);
dropbox.addEventListener("dragover", dragOver, false);
dropbox.addEventListener("drop", drop, false);

function dragEnter(evt) {
  evt.stopPropagation();
  evt.preventDefault();
}

function dragExit(evt) {
	evt.stopPropagation();
	evt.preventDefault();
}

function dragOver(evt) {
  evt.stopPropagation();
  evt.preventDefault();
}

function drop(evt) {
	evt.stopPropagation();
	evt.preventDefault();

	var files = evt.dataTransfer.files;
	var count = files.length;

	// Only call the handler if 1 or more files was dropped.
	if (count > 0) {
		handleFiles(files);
	}
}

function handleFiles(files) {
	var file = files[0];
	document.getElementById("droplabel").innerHTML = "Processing...";
	
	var reader = new FileReader();
	
	// init the reader event handlers
	reader.onload = handleReaderLoad;

	// begin the read operation
	reader.readAsDataURL(file);
}

function handleReaderLoad(evt) {
  var img = document.getElementById("preview");
  img.src = evt.target.result;
}