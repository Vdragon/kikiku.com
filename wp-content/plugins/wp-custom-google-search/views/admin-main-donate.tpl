    <p>{ADMIN_MENU_OPTIONS_DONATION_DESC}</p>
    <form method="post" action="{ADMIN_MENU_OPTIONS_DONATION_LINK_PAYPAL}">
        <div class="paypal-donations">
            <input type="hidden" value="{ADMIN_MENU_OPTIONS_DONATION_OPTION_CMD}" name="cmd">
            <input type="hidden" value="{ADMIN_MENU_OPTIONS_DONATION_OPTION_BUSINESS}" name="business">
            <input type="hidden" value="{ADMIN_MENU_OPTIONS_DONATION_OPTION_RETURN}" name="return">
            <input type="hidden" value="{ADMIN_MENU_OPTIONS_DONATION_OPTION_ITEM}" name="item_name">
            <input type="hidden" value="{ADMIN_MENU_OPTIONS_DONATION_OPTION_AMOUNT}" name="amount">
            <input type="hidden" value="{ADMIN_MENU_OPTIONS_DONATION_OPTION_CURRENCY}" name="currency_code">
            <input type="image" alt="{ADMIN_MENU_OPTIONS_DONATION_OPTION_IMGALT}" name="submit" src="{ADMIN_MENU_OPTIONS_DONATION_OPTION_IMGSRC}">
            <img width="1" height="1" src="{ADMIN_MENU_OPTIONS_DONATION_OPTION_PIXEL_IMGSRC}" alt="">
        </div>
    </form>
    <p>{ADMIN_MENU_OPTIONS_DONATION_VISIT_LABEL} <a target="_blank" href="{ADMIN_MENU_OPTIONS_DONATION_LINK}" title="{ADMIN_MENU_OPTIONS_DONATION_LINK_TEXT}">{ADMIN_MENU_OPTIONS_DONATION_LINK_TEXT}</a></p>
</div>