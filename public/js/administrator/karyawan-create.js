function imageToBig(){
    window.alert("Maximal of image size is 1 Mb");
}

function pdfToBig(){
    window.alert("Maximal of PDF size is 5 Mb");
}

$("#foto_cv").on("change", function() {
    var files = $('#foto_cv')[0].files;
    if(files[0].size > 1000000 && files[0].type.match('image')){
        console.log(files[0].size)
        $("#foto_cv").val('')
        $(".preview_cv").hide();
        imageToBig();
    }
    else if(files[0].size > 5000000 && files[0].type.match('pdf')){
        console.log(files[0].size)
        $("#foto_cv").val('')
        $(".preview_cv").hide();
        pdfToBig();
    }
    showFileCV()
});

function showFileCV(){
    if (window.File && window.FileList && window.FileReader) {
        var files = event.target.files; //FileList object
        var output = document.getElementById("result_cv");
        $("#result_cv").html("");
        if (files.length) {
            $(".show_cv").hide();
            $(".preview_cv").show();
        } else {
            $(".preview_cv").hide();
            $(".show_cv").show();
        }
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                //Only pics
                if (!file.type.match('image') && !file.type === 'application/pdf')
                    continue;
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                    var picFile = event.target;
                    var div = document.createElement("div");
                    if(!file.type.match('image')){
                        $("#result_cv").html("<embed src='" + picFile.result + "' frameborder='0' width='100%' height='400px'>");
                    } else {
                        div.innerHTML = "<img src='" + picFile.result + "' style='width: 100%' />";
                    }
                    output.insertBefore(div, null);
                });
                //Read the image
                picReader.readAsDataURL(file);
            }
    } else {
        console.log("Your browser does not support File API");
    }
}

$("#foto_sim").on("change", function() {
    var files = $('#foto_sim')[0].files;
    if(files[0].size > 1000000 && files[0].type.match('image')){
        console.log(files[0].size)
        $("#foto_sim").val('')
        $(".preview_sim").hide();
        imageToBig();
    }
    else if(files[0].size > 5000000 && files[0].type.match('pdf')){
        console.log(files[0].size)
        $("#foto_sim").val('')
        $(".preview_sim").hide();
        pdfToBig();
    }
    showFileSIM()
});

function showFileSIM(){
    if (window.File && window.FileList && window.FileReader) {
        var files = event.target.files; //FileList object
        var output = document.getElementById("result_sim");
        $("#result_sim").html("");
        if (files.length) {
            $(".show_sim").hide();
            $(".preview_sim").show();
        } else {
            $(".preview_sim").hide();
            $(".show_sim").show();
        }
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                //Only pics
                if (!file.type.match('image') && !file.type === 'application/pdf')
                    continue;
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                    var picFile = event.target;
                    var div = document.createElement("div");
                    if(!file.type.match('image')){
                        $("#result_sim").html("<embed src='" + picFile.result + "' frameborder='0' width='100%' height='400px'>");
                    } else {
                        div.innerHTML = "<img src='" + picFile.result + "' style='width: 100%' />";
                    }
                    output.insertBefore(div, null);
                });
                //Read the image
                picReader.readAsDataURL(file);
            }
    } else {
        console.log("Your browser does not support File API");
    }
}

$("#foto_kk").on("change", function() {
    var files = $('#foto_kk')[0].files;
    if(files[0].size > 1000000 && files[0].type.match('image')){
        console.log(files[0].size)
        $("#foto_kk").val('')
        $(".preview_kk").hide();
        imageToBig();
    }
    else if(files[0].size > 5000000 && files[0].type.match('pdf')){
        console.log(files[0].size)
        $("#foto_kk").val('')
        $(".preview_kk").hide();
        pdfToBig();
    }
    showFileKK()
});

function showFileKK(){
    if (window.File && window.FileList && window.FileReader) {
        var files = event.target.files; //FileList object
        var output = document.getElementById("result_kk");
        $("#result_kk").html("");
        if (files.length) {
            $(".show_kk").hide();
            $(".preview_kk").show();
        } else {
            $(".preview_kk").hide();
            $(".show_kk").show();
        }
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                //Only pics
                if (!file.type.match('image') && !file.type === 'application/pdf')
                    continue;
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                    var picFile = event.target;
                    var div = document.createElement("div");
                    if(!file.type.match('image')){
                        $("#result_kk").html("<embed src='" + picFile.result + "' frameborder='0' width='100%' height='400px'>");
                    } else {
                        div.innerHTML = "<img src='" + picFile.result + "' style='width: 100%' />";
                    }
                    output.insertBefore(div, null);
                });
                //Read the image
                picReader.readAsDataURL(file);
            }
    } else {
        console.log("Your browser does not support File API");
    }
}

$("#foto_ktp").on("change", function() {
    var files = $('#foto_ktp')[0].files;
    if(files[0].size > 1000000 && files[0].type.match('image')){
        console.log(files[0].size)
        $("#foto_ktp").val('')
        $(".preview_ktp").hide();
        imageToBig();
    }
    else if(files[0].size > 5000000 && files[0].type.match('pdf')){
        console.log(files[0].size)
        $("#foto_ktp").val('')
        $(".preview_ktp").hide();
        pdfToBig();
    }
    showFileKTP()
});

function showFileKTP(){
    if (window.File && window.FileList && window.FileReader) {
        var files = event.target.files; //FileList object
        var output = document.getElementById("result_ktp");
        $("#result_ktp").html("");
        if (files.length) {
            $(".show_ktp").hide();
            $(".preview_ktp").show();
        } else {
            $(".preview_ktp").hide();
            $(".show_ktp").show();
        }
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                //Only pics
                if (!file.type.match('image') && !file.type === 'application/pdf')
                    continue;
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                    var picFile = event.target;
                    var div = document.createElement("div");
                    if(!file.type.match('image')){
                        $("#result_ktp").html("<embed src='" + picFile.result + "' frameborder='0' width='100%' height='400px'>");
                    } else {
                        div.innerHTML = "<img src='" + picFile.result + "' style='width: 100%' />";
                    }
                    output.insertBefore(div, null);
                });
                //Read the image
                picReader.readAsDataURL(file);
            }
    } else {
        console.log("Your browser does not support File API");
    }
}

$("#modal-certificate_photo").on("change", function() {
    var files = $('#modal-certificate_photo')[0].files;
    if(files[0].size > 1000000 && files[0].type.match('image')){
        console.log(files[0].size)
        $("#modal-certificate_photo").val('')
        $(".preview_certificate").hide();
        imageToBig();
    }
    else if(files[0].size > 5000000 && files[0].type.match('pdf')){
        console.log(files[0].size)
        $("#modal-certificate_photo").val('')
        $(".preview_certificate").hide();
        pdfToBig();
    }
    showFileCertificate()
});

function showFileCertificate(){
    if (window.File && window.FileList && window.FileReader) {
        var files = event.target.files; //FileList object
        var output = document.getElementById("result_certificate");
        $("#result_certificate").html("");
        if (files.length) {
            $(".preview_certificate").show();
        } else {
            $(".preview_certificate").hide();
        }
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                //Only pics
                if (!file.type.match('image') && !file.type === 'application/pdf')
                    continue;
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                    var picFile = event.target;
                    var div = document.createElement("div");
                    if(!file.type.match('image')){
                        $("#result_certificate").html("<embed src='" + picFile.result + "' frameborder='0' width='100%' height='400px'>");
                    } else {
                        div.innerHTML = "<img src='" + picFile.result + "' style='width: 100%' />";
                    }
                    output.insertBefore(div, null);
                });
                //Read the image
                picReader.readAsDataURL(file);
            }
    } else {
        console.log("Your browser does not support File API");
    }
}

$("#modal-file_contract").on("change", function() {
    var files = $('#modal-file_contract')[0].files;
    if(files[0].size > 1000000 && files[0].type.match('image')){
        console.log(files[0].size)
        $("#modal-file_contract").val('')
        $(".preview_contract").hide();
        imageToBig();
    }
    else if(files[0].size > 5000000 && files[0].type.match('pdf')){
        console.log(files[0].size)
        $("#modal-file_contract").val('')
        $(".preview_contract").hide();
        pdfToBig();
    }
    showFileContract()
});

function showFileContract(){
    if (window.File && window.FileList && window.FileReader) {
        var files = event.target.files; //FileList object
        var output = document.getElementById("result_contract");
        $("#result_contract").html("");
        if (files.length) {
            $(".preview_contract").show();
        } else {
            $(".preview_contract").hide();
        }
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                //Only pics
                if (!file.type.match('image') && !file.type === 'application/pdf')
                    continue;
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                    var picFile = event.target;
                    var div = document.createElement("div");
                    if(!file.type.match('image')){
                        $("#result_contract").html("<embed src='" + picFile.result + "' frameborder='0' width='100%' height='400px'>");
                    } else {
                        div.innerHTML = "<img src='" + picFile.result + "' style='width: 100%' />";
                    }
                    output.insertBefore(div, null);
                });
                //Read the image
                picReader.readAsDataURL(file);
            }
    } else {
        console.log("Your browser does not support File API");
    }
}
