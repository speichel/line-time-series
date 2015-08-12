
function validate()
        {
            if (!document.getElementById("fileToUpload").files.length > 0)
            {
               alert("No file uploaded.");
               return false;
            } 
			return true;
        }
		
function LinkCheck()
{
    var http = new XMLHttpRequest();
    http.open('HEAD', 'uploads/BigBlueRAW.txt', false);
    http.send();
    return http.status!=404;
	alert("File Exist.");
}