var Account = {
    primaryPageName:'',
    secondaryPageNumber:0,
    statics: {
        PROFILE:'my account',
        DEPOSIT:'deposit',
        WITHDRAW:'withdrawal',
        PLACE_ORDER:'place order',
        ORDER_BOOK:'order book',
        TRANSFER:'transfer',
        HISTORY:'history',
        VERIFY:'verify'
    },
    init:function() {
        Account.clearConfirmations();
        $('.account-content').hide();
        $('.nav-pills').on('click',function(e) {
            e.preventDefault();
            Account.changePage(e);
        });
        Account.activatePrimaryPage('');

    },
    activatePrimaryPage:function(pageName) {
        Account.primaryPageName = pageName.toLowerCase();
        switch(Account.primaryPageName) {
            case Account.statics.PROFILE:
                $('.account-profile').show();
                break;
            case Account.statics.DEPOSIT:
                $('.account-deposit').show();
                break;
            case Account.statics.WITHDRAW:
                $('.account-withdrawal').show();
                break;
            case Account.statics.PLACE_ORDER:
                $('.account-placeorder').show();
                break;
            case Account.statics.ORDER_BOOK:
                document.location = 'orders.html';
                break;
            case Account.statics.TRANSFER:
                $('.account-transfer').show();
                break;
            case Account.statics.HISTORY:
                $('.account-history').show();
                break;
            case Account.statics.VERIFY:
                $('.account-verify').show();
                break;
            default:
                $('.account-profile').show();
                break;
        }
    },
    activateSecondaryPage:function() {
        var accountString = '.account-'+Account.primaryPageName.replace(' ','');
        var accountSecondaryString = ' .page'+Account.secondaryPageNumber;
        var accountSelector = accountString+accountSecondaryString;
        $(accountSelector).show();
    },
    changePage:function(event) {


        $('#form_next,#transaction_detail_etf_confirm,#form_Continue').on('click', function(e) {
            e.preventDefault();
        });


        $('.account-content').hide();
        $('.pages').hide();
        $('.account-heading').html($(event.target).html());
        Account.secondaryPageNumber = 0;
        Account.activatePrimaryPage($(event.target).html());
        Account.activateSecondaryPage();
    },
    changeSecondaryPage:function(goToPage) {
        Account.secondaryPageNumber = goToPage;
        Account.activateSecondaryPage();
    },
    clearConfirmations:function() {
        $('.confirmation').hide();
    },
    clearSecondaryPages:function() {
        $('.pages').hide();
    }
}
