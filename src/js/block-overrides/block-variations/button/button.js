
import { registerBlockVariation } from "@wordpress/blocks";
import { __ } from '@wordpress/i18n';


registerBlockVariation("core/button", {
    name: "dpuk-company-email",
    title: "DPUK Company Email",
    icon: "email",
    attributes: {
        className: "dpuk-company-email-block-binding",
        metadata: {
            name: "DPUK Company Email",
            bindings: {
                url: { source: "dpuk/company-email-binding", args: { key: "dpuk_acf_company_email" } },
            },
        },
        text: "DPUK Company Emails",
    },
});