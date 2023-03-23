window.onload = () => {
    function getParam(name) {
        return getQueryVariable(name) || getCookie(name);
    }

    function getQueryVariable(variable) {
        const query = window.location.search.substring(1);
        const vars = query.split("&");
        for (let i = 0; i < vars.length; i++) {
            const pair = vars[i].split("=");
            if (pair[0] === variable) {
                return pair[1];
            }
        }
        return false;
    }

    function setCookie(name, value, days) {
        let expires;

        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
    }

    function getCookie(name) {
        const nameEQ = encodeURIComponent(name) + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ')
                c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0)
                return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
        return null;
    }

    const fbclid = getParam('fbclid');
    const pixel_id = getParam('pixel_id') || '';
    const utm_campaign = getParam('utm_campaign');
    const adsetname = getParam('adsetname') || getParam('adset_name');
    const adname = getParam('adname') || getParam('ad_name');
    const utm_medium = getParam('utm_medium');
    const utm_source = getParam('utm_source');
    const utm_content = getParam('utm_content');

    function getEventData() {
        if (!fbclid) {
            return false;
        }

        const site = getParam('domain') || window.location.hostname;
        const userId = getCookie('userId');
        const referer = document.referrer || getCookie('referer') || 'direct';

        return {
            fbclid,
            referer,
            pixel_id,
            utm_campaign,
            adsetname,
            adname,
            utm_medium,
            utm_source,
            utm_content,
            site,
            userId,
        };
    }

    if (fbclid) {
        setCookie('fbclid', fbclid, 180);

        const domain = getParam('domain');
        if (domain) {
            setCookie('domain', domain, 180);
        }

        const referer = document.referrer || getCookie('referer');
        if (referer) {
            setCookie('referer', referer, 180);
        }

        fetch(`https://trackfb.${window.location.hostname}/v`, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(getEventData())
        }).then(res => {
            res.json().then(answer => {
                if (answer.status === 'ok') {
                    setCookie('userId', answer.data);
                }

                const buttons = document.getElementsByClassName('start-button');
                for (let i = 0; i < buttons.length; i++) {
                    if (answer.remarketingData) {
                        const { offerId, affiliateId, fbclid } = answer.remarketingData;
                        buttons[i].href = `https://go-site.xyz/aff_c?offer_id=${offerId}&aff_id=${affiliateId}&click_id=${fbclid}&aff_sub5=remarketing`;
                    } else {
                        let url = buttons[i].href;
                        url += url.indexOf('?') < 0 ? '?' : '&';
                        url += 'click_id=' + fbclid;
                        if (pixel_id) {
                            url += '&aff_sub=' + pixel_id;
                        }
                        if (utm_campaign) {
                            url += '&aff_sub2=' + utm_campaign;
                            setCookie('utm_campaign', utm_campaign, 180);
                        }
                        if (adsetname) {
                            url += '&aff_sub3=' + adsetname;
                            setCookie('adsetname', adsetname, 180);
                        }
                        if (adname) {
                            url += '&aff_sub4=' + adname;
                            setCookie('adname', adname, 180);
                        }
                        if (utm_medium) {
                            url += '&aff_sub5=' + utm_medium;
                            setCookie('utm_medium', utm_medium, 180);
                        }
                        if (utm_source) {
                            url += '&source=' + utm_source;
                            setCookie('utm_source', utm_source, 180);
                        }
                        buttons[i].href = url;
                    }
                }
            });
        });
    }
}
