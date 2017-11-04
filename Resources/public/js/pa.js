$('document').ready(function () {
    $(".help").popover({placement: 'left', html: true});
});

function lancerUneAnalyse(one) {   
    $('#formLanceur').hide();
    $('#refreshLanceur').show();
    $.ajax({
        url: urlLanceur,
        data: {
            'one' : one
        },
        method: 'post'
    }).done(function (data) {
        setTimeout(refreshLanceur, 3000);
    });

    return false;
}

function lancerAnalyse() {
    $('#formLanceur').hide();
    $('#refreshLanceur').show();

    var genDoc = false;
    if (document.getElementById('genCC') && document.getElementById('genDoc').checked) {
        genDoc = 1;
    }
    var genCC = false;
    if (document.getElementById('genCC') && document.getElementById('genCC').checked) {
        genCC = 1;
    }

    $.ajax({
        url: urlLanceur,
        data: {
            genDoc: genDoc,
            genCC: genCC
        },
        method: 'post'
    }).done(function (data) {
        setTimeout(refreshLanceur, 3000);
    });

    return false;
}

function refreshLanceur() {
    $('#formLanceur').hide();
    $('#refreshLanceur').show();
    $.ajax({
        url: urlLanceur+"?statut=1"
    }).done(function (data) {
        if (data.trim() == 'ok') {
            $('#rechargePage').show();
            $('#refreshLanceur').hide();
        } else {
            setTimeout(refreshLanceur, 3000);
        }
    });
}
