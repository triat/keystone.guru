class CommonFormsCreatetemporaryroute extends InlineCode {
    /**
     *
     */
    activate() {
        super.activate();

        new LevelSliderInitializer(this.options);
    }
}
