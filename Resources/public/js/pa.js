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
    $.ajax({
        url: urlLanceur,
        data: {
            genDoc: document.getElementById('genDoc').checked * 1,
            genCC: document.getElementById('genCC').checked * 1
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
