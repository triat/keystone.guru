class SearchFilterUser extends SearchFilterInputText {
    constructor(selector, onChange) {
        super({
            name: 'user',
            default: '',
            selector: selector,
            onChange: onChange
        });
    }
}