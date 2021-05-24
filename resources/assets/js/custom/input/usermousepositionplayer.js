/**
 * The animation for this class is done using CSS - there's no smoothing going on here. See .user_mouse_position css class definition
 * We just apply the position at specific times and the css animation handles the rest
 */
class UserMousePositionPlayer extends Signalable {
    /**
     *
     * @param mapobject {UserMousePosition}
     * @param message {MousePositionMessage}
     * @param previousPlayer {UserMousePositionPlayer|null}
     */
    constructor(mapobject, message, previousPlayer) {
        super();

        this.mapobject = mapobject;
        this.message = message;

        this.handles = [];
    }

    /**
     *
     * @param point
     * @private
     */
    _setPosition(point) {
        this.mapobject.setPosition(point.lat, point.lng);
    }

    /**
     * Starts applying the received mouse positions over time.
     */
    start() {
        for (let i = 0; i < this.message.points.length; i++) {
            let point = this.message.points[i];

            this.handles.push(setTimeout(this._setPosition.bind(this, point), point.time));
        }
    }

    /**
     * Stops parsing any mouse positions.
     */
    stop() {
        for (let i = 0; i < this.handles.length; i++) {
            // Clear all timeouts (even if they were already handled)
            clearTimeout(this.handles[i]);
        }

        this.handles = [];
    }
}