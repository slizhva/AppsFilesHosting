document.addEventListener("DOMContentLoaded", function () {
    const $copyLinkButton = $('#copyLinkButton')
    if ($copyLinkButton) {
        navigator.permissions.query({name: "clipboard-write"}).then((result) => {
            if (result.state === "granted" || result.state === "prompt") {
                $copyLinkButton.on('click', () => {
                    navigator.clipboard.writeText($('#textToCopy').val());
                    $copyLinkButton.val('Copied!')
                    setTimeout(() => {
                        $copyLinkButton.val('Copy Link')
                    }, 2000)
                })

                $copyLinkButton.show()
            }
        });
    }
});