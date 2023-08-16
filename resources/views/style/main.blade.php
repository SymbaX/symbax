<style>
    :root {
        --required-label: "{{ __('labels.required') }}";
        --optional-label: "{{ __('labels.optional') }}";
    }

    .required::after {
        content: var(--required-label);
        background-color: rgb(236, 166, 100);
        color: #fff;
        font-size: 10px;
        font-weight: bold;
        min-width: 10px;
        padding: 3px 7px;
        margin: 0px 5px;
        line-height: 1;
        vertical-align: middle;
        white-space: nowrap;
        text-align: center;
        border-radius: 10px;
        display: inline-block;
    }

    .optional::after {
        content: var(--optional-label);
        background-color: rgb(176, 176, 176);
        color: #fff;
        font-size: 10px;
        font-weight: bold;
        min-width: 10px;
        padding: 3px 7px;
        margin: 0px 5px;
        line-height: 1;
        vertical-align: middle;
        white-space: nowrap;
        text-align: center;
        border-radius: 10px;
        display: inline-block;
    }
</style>
