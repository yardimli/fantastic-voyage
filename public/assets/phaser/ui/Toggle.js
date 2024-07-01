const defaultFrame = {
    frame: '',
    offsetX: 0,
    offsetY: 0,
    alpha: 1,
    tint: [ 0xffffff, 0xffffff, 0xffffff, 0xffffff ],
    scale: 1,
    angle: 0
};

const defaultLabel = {
    text: '',
    isBitmapText: false,
    offsetX: 0,
    offsetY: 0,
    alpha: 1,
    tint: [ 0xffffff, 0xffffff, 0xffffff, 0xffffff ],
    scale: 1,
    angle: 0,
    font: '',
    size: 0,
    spacing: 0,
    color: '#ffffff',
    style: '',
    align: 'right',
    buttonAlign: 'left',
    inputEnabled: false,
    shadow: {
        offsetX: 1,
        offsetY: 1,
        blur: 1,
        fill: true,
        stroke: false,
        color: '#000000'
    }
};

const defaultSound = {
    key: '',
    config: {
        volume: 1,
        rate: 1,
        detune: 0,
        seek: 0,
        loop: false,
        delay: 0
    },
    marker: {
        name: '',
        start: 0,
        duration: 0
    }
};

function GetDefaultState (type)
{
    return {
        type,
        frame: Phaser.Utils.Objects.Extend(true, {}, defaultFrame),
        icon: Phaser.Utils.Objects.Extend(true, {}, defaultFrame),
        label: Phaser.Utils.Objects.Extend(true, {}, defaultLabel),
        sound: Phaser.Utils.Objects.Extend(true, {}, defaultSound)
    };
}

function GetValue (source, key, defaultValue = null)
{
    var t = typeof(source);

    if (!source || t === 'number' || t === 'string')
    {
        return defaultValue;
    }
    else if (source.hasOwnProperty(key) && source[key] !== undefined)
    {
        return source[key];
    }
    else
    {
        return defaultValue;
    }
}

function GetFrame (source, defaultFrame = null)
{
    if (!source || source === '')
    {
        return defaultFrame;
    }
    else if (typeof source === 'string')
    {
        return source;
    }
    else
    {
        return GetValue(source, 'frame', defaultFrame);
    }
}

function SetFrame (source, target, defaultFrame = null)
{
    target.frame = GetFrame(source, defaultFrame);
}

function GetTint (source, defaultTint = [ 0xffffff, 0xffffff, 0xffffff, 0xffffff ], defaultAlpha = 1)
{
    let configTint = GetValue(source, 'tint');
    let tint = defaultTint;

    if (Array.isArray(configTint))
    {
        if (configTint.length === 1)
        {
            tint = [ configTint[0], configTint[0], configTint[0], configTint[0] ];
        }
        else if (configTint.length === 2)
        {
            tint = [ configTint[0], configTint[0], configTint[1], configTint[1] ];
        }
        else if (configTint.length === 4)
        {
            tint = [ configTint[0], configTint[1], configTint[2], configTint[3] ];
        }
    }
    else if (typeof configTint === 'number')
    {
        tint = [ configTint, configTint, configTint, configTint ];
    }

    const alpha = GetValue(source, 'alpha', defaultAlpha);

    return {
        tint,
        alpha
    };
}

function SetTint (source, target, defaultTint = [ 0xffffff, 0xffffff, 0xffffff, 0xffffff ], defaultAlpha = 1)
{
    let { tint, alpha } = GetTint(source, defaultTint, defaultAlpha);

    target.tint = tint;
    target.alpha = alpha;
}

function GetTransform (source, x = 0, y = 0, defaultScale = 1, defaultAngle = 0)
{
    const offsetX = GetValue(source, 'offsetX', x);
    const offsetY = GetValue(source, 'offsetY', y);
    const scale = GetValue(source, 'scale', defaultScale);
    const angle = GetValue(source, 'angle', defaultAngle);

    return {
        offsetX,
        offsetY,
        scale,
        angle
    };
}

function SetTransform (source, target, x = 0, y = 0, defaultScale = 1, defaultAngle = 0)
{
    let { offsetX, offsetY, scale, angle } = GetTransform(source, x, y, defaultScale, defaultAngle);

    target.offsetX = offsetX;
    target.offsetY = offsetY;
    target.scale = scale;
    target.angle = angle;
}

function SetLabel (source, target, defaultConfig)
{
    if (!defaultConfig)
    {
        defaultConfig = defaultLabel;
    }

    target.font = GetValue(source, 'font', defaultConfig.font);
    target.text = GetValue(source, 'text', defaultConfig.text);
    target.isBitmapText = GetValue(source, 'isBitmapText', defaultConfig.isBitmapText);
    target.size = GetValue(source, 'size', defaultConfig.size);
    target.spacing = GetValue(source, 'spacing', defaultConfig.spacing);
    target.color = GetValue(source, 'color', defaultConfig.color);
    target.align = GetValue(source, 'align', defaultConfig.align);
    target.buttonAlign = GetValue(source, 'buttonAlign', defaultConfig.buttonAlign);
    target.inputEnabled = GetValue(source, 'inputEnabled', defaultConfig.inputEnabled);
    target.style = GetValue(source, 'style', defaultConfig.style);
    target.shadow = Phaser.Utils.Objects.MergeRight(defaultConfig.shadow, GetValue(source, 'shadow'));
}

function SetSound (source, target, defaultConfig)
{
    if (!defaultConfig)
    {
        defaultConfig = defaultSound;
    }

    const key = GetValue(source, 'key', null);
        
    if (key)
    {
        target.key = key;

        const soundConfig = GetValue(source, 'config');
        const markerConfig = GetValue(source, 'marker');

        if (markerConfig)
        {
            target.config = Phaser.Utils.Objects.MergeRight(defaultSound.marker, markerConfig);

            if (soundConfig)
            {
                target.config.config = Phaser.Utils.Objects.MergeRight(defaultSound.config, soundConfig);
            }
        }
        else if (soundConfig)
        {
            target.config = Phaser.Utils.Objects.MergeRight(defaultSound.config, soundConfig);
        }

        return true;
    }
    else
    {
        return false;
    }
}

export const ON = 0;
export const OFF = 1;
export const DISABLED = 2;

/**
 * @typedef {Object} FrameConfig
 * @property {string} [frame] - The key of the frame to use as the image. Must exist within the texture key.
 * @property {number} [offsetX=0] - The horizontal offset of the frame from the button center in this state.
 * @property {number} [offsetY=0] - The vertical offset of the frame from the button center in this state.
 * @property {number} [scale=1] - The scale applied the frame in this state.
 * @property {number} [angle=0] - The angle applied the frame in this state.
 * @property {(number|number[])} [tint] - The tint applied the frame in this state.
 * @property {number} [alpha=1] - The alpha applied the frame in this state.
 */

/**
 * @typedef {Object} LabelShadowConfig
 * @property {number} [offsetX=0] - The horizontal offset of the label from the button center.
 * @property {number} [offsetY=0] - The vertical offset of the label from the button center.
 * @property {number} [blur=1] - The amount of blur on the shadow.
 * @property {boolean} [fill=true] - Should the shadow be applied to the fill?
 * @property {boolean} [stroke=false] - Should the shadow be applied to the stroke?
 * @property {string} [color='#000000'] - The color of the shadow.
 */

/**
 * @typedef {Object} LabelConfig
 * @property {string} [text=''] - The text to display on the label.
 * @property {boolean} [isBitmapText=false] - Should the label render with a Text or BitmapText object?
 * @property {number} [offsetX=0] - The horizontal offset of the label from the button center.
 * @property {number} [offsetY=0] - The vertical offset of the label from the button center.
 * @property {(number|number[])} [tint] - The tint to be applied to the label.
 * @property {number} [scale=1] - The label scale.
 * @property {number} [angle=0] - The label angle, given in degrees.
 * @property {string} [font=''] - The font the label uses to render with.
 * @property {number} [size=0] - The size of the font.
 * @property {string} [color='#ffffff'] - Text Only. The color of the font.
 * @property {string} [style=''] - Text Only. A style to apply to the text, such as 'bold'.
 * @property {string} [align='center'] - The alignment of multi-line text labels.
 * @property {string} [buttonAlign='left'] - The alignment of the label in relation to the toggle.
 * @property {boolean} [inputEnabled=false] - Will clicking the label activate the toggle?
 * @property {LabelShadowConfig} [shadow] - Text Only. The shadow configuration object.
 */

/**
 * Config object containing various sound settings.
 *
 * @typedef {object} SoundConfig
 * @property {boolean} [mute=false] - Boolean indicating whether the sound should be muted or not.
 * @property {number} [volume=1] - A value between 0 (silence) and 1 (full volume).
 * @property {number} [rate=1] - Defines the speed at which the sound should be played.
 * @property {number} [detune=0] - Represents detuning of sound in [cents](https://en.wikipedia.org/wiki/Cent_%28music%29).
 * @property {number} [seek=0] - Position of playback for this sound, in seconds.
 * @property {boolean} [loop=false] - Whether or not the sound or current sound marker should loop.
 * @property {number} [delay=0] - Time, in seconds, that should elapse before the sound actually starts its playback.
 */

/**
 * Marked section of a sound represented by name, and optionally start time, duration, and config object.
 *
 * @typedef {Object} SoundMarker
 * @property {string} name - Unique identifier of a sound marker.
 * @property {number} [start=0] - Sound position offset at witch playback should start.
 * @property {number} [duration] - Playback duration of this marker.
 * @property {Phaser.Types.Sound.SoundConfig} [config] - An optional config object containing default marker settings.
 */

/**
 * @typedef {Object} SoundConfig
 * @property {string} [key=''] - The key of the loaded audio file to play for this sound.
 * @property {SoundConfig} [config] - The sound configuration object that controls settings like volume and rate.
 * @property {SoundMarker} [marker] - The sound marker configuration object if the sound is part of an Audio Sprite.
 */

/**
 * @typedef {Object} ButtonStateConfig
 * 
 * @property {(string|FrameConfig)} [frame] - The frame configuration object. If a string will be set as the frame key.
 * @property {(string|FrameConfig)} [icon] - The icon frame configuration object. If a string will be set as the icon frame key.
 * @property {(string|LabelConfig)} [label] - The label frame configuration object. If a string will be set as the label text.
 * @property {(string|SoundConfig)} [sound] - The sound configuration object. If a string will be set as the sound file key.
 * @property {number} [offsetX=0] - The horizontal offset applied to all elements in the button state.
 * @property {number} [offsetY=0] - The vertical offset applied to all elements in the button state.
 * @property {number} [scale=1] - The scale applied to all elements in the button state.
 * @property {number} [angle=0] - The angle applied to all elements in the button state.
 * @property {(number|number[])} [tint] - The tint applied to all elements in the button state.
 * @property {number} [alpha=1] - The alpha applied to all elements in the button state.
 */

export default class Toggle extends Phaser.GameObjects.Container
{
    /**
     * Creates a new `Toggle` object. A Toggle is a UI component that allows you to handle creating
     * a toggle button in your game, automatically handling the input events, including changing the displayed frame and label,
     * and playing sound effects, if required.
     * 
     * The three states a Toggle can be in are:
     * 
     * * `On` - The default state. No pointer is over the button and it's enabled, waiting for input.
     * * `Off` - When a pointer moves over the button. This is also commonly known as 'hover'.
     * * `Disabled` - The button has been disabled and no pointer events will run on it.
     * 
     * A different texture frame, text label and sound effect can be specified for any of the states.
     * 
     * Visually, a Toggle consists of 3 Game Objects inside a Container:
     * 
     * * The Background (`Toggle.button` Image)
     * * The Icon (optional) (`Toggle.icon` Image)
     * * The Text Label (optional) (`Toggle.label` Text or BitmapText)
     * 
     * In order to save space in your texture atlases you can create a background graphic for your toggle,
     * and then lots of different icons to display over the top of it.
     * 
     *  In the example below, the large yellow
     * and pink buttons don't have any icon drawn on them in the PNG. Instead, the icon is composited on
     * via the `outIcon` configuration property. This can reduce your button set file sizes considerably.
     * 
     * Every icon can be provided in one of the 5 different button states, as with the backgrounds.
     * 
     * By default, icons are centered on the background frame. You canset the `iconOffsetX` and `iconOffsetY`
     * properties to adjust this placement for all icons, or you can set the offset direct in the icon
     * property itself, i.e.: `downIcon: { yOffset: 8 }`.
     * 
     * As with backgrounds, icons can be tinted in the same way. See the tint demo for more details.
     * 
     * As with text labels, icons are optional, so you don't have to use them. But, for lots of button
     * styles, they make a lot of sense to take advantage of.
     * 
     * Frames can be specified as either an integer (the frame ID), or a string (the frame name).
     * 
     * @param {Object} config - The Button Configuration object.
     * @param {Phaser.Scene} config.scene - The Scene to which you are adding this Button.
     * @param {number} [config.x=0] - The x coordinate of the Button.
     * @param {number} [config.y=0] - The y coordinate of the Button.
     * @param {string} [config.texture] - The texture key the button frames use. Should be a sprite sheet or texture atlas key.
     * @param {(string|LabelConfig)} [config.label] - The label configuration object. If a string will be set as the label text.
     * @param {(string|FrameConfig)} [config.icon] - The icon configuration object. If a string it sets the icon frame.
     * @param {(string|ButtonStateConfig)} [config.out] - The out state configuration object. If a string it sets the background frame.
     * @param {(string|ButtonStateConfig)} [config.over] - The over state configuration object. If a string it sets the background frame.
     * @param {(string|ButtonStateConfig)} [config.down] - The down state configuration object. If a string it sets the background frame.
     * @param {(string|ButtonStateConfig)} [config.up] - The up state configuration object. If a string it sets the background frame.
     * @param {(string|ButtonStateConfig)} [config.disabled] - The disabled state configuration object. If a string it sets the background frame.
     * @param {boolean} [config.enabled=true] - Does the button start enabled or disabled? If no disabled frame is provided, it just won't interact.
     * @param {boolean} [config.forceOut=false] - When the pointer is released should the button be forced to an out state?
     * @param {boolean} [config.labelOnTop=true] - Does the label appear over the top of the icon (true), or below it? (false)
     * @param {number} [config.originX=0.5] - The horizontal origin of the button.
     * @param {number} [config.originY=0.5] - The vertical origin of the button.
     * @param {number} [config.radius=0] - If you'd rather use a circle hit area than a rectangle, set the radius of the button in this property.
     * @param {(Phaser.Types.Input.InputConfiguration|any)} [config.hitArea] - Either an input configuration object, or a geometric shape that defines the hit area for the Button. If not specified a Rectangle will be used.
     * @param {Phaser.Types.Input.HitAreaCallback} [config.hitAreaCallback] - A callback to determine if the Button should be interacted with. If you provide a hitArea shape you must also provide a callback.
     * @param {string} [config.key] - A string denoting which key press will optionally activate this button.
     * @memberof Toggle
     */
    constructor ({
        scene,
        x = 0,
        y = 0,
        texture = '',
        label = null,
        icon = null,
        on = null,
        off = null,
        disabled = null,
        checked = true,
        enabled = true,
        originX = 0.5,
        originY = 0.5,
        radius = 0,
        hitArea = null,
        hitAreaCallback = null,
        key = null
    })
    {
        super(scene, x, y);

        this.checked = checked;
        this.enabled = enabled;

        if (icon && typeof icon === 'string')
        {
            icon = { frame: icon };
        }
        else if (!icon && ((on && on.hasOwnProperty('icon')) || (off && off.hasOwnProperty('icon'))))
        {
            icon = {};
        }

        if (label && typeof label === 'string')
        {
            label = { text: label };
        }

        this.globals = {
            icon: (icon) ? Phaser.Utils.Objects.MergeRight(defaultFrame, icon) : null,
            label: (label) ? Phaser.Utils.Objects.MergeRight(defaultLabel, label) : null
        };

        this.states = {
            on: GetDefaultState(ON),
            off: GetDefaultState(OFF),
            disabled: GetDefaultState(DISABLED)
        };

        this.statesMap = [
            this.states.on,
            this.states.off,
            this.states.disabled
        ];

        this.stateConstMap = {
            'on': ON,
            'off': OFF,
            'disabled': DISABLED
        };

        this.setOnState(on);
        this.setOffState(off);
        this.setDisabledState(disabled);

        //  Create the Game Objects

        this.button = scene.add.image(0, 0, texture);

        this.add(this.button);

        if (icon)
        {
            this.icon = scene.add.image(0, 0, texture, this.globals.icon.frame);

            this.add(this.icon);
        }

        if (label)
        {
            const labelData = this.globals.label;

            if (labelData.isBitmapText)
            {
                this.label = scene.add.bitmapText(labelData.offsetX, labelData.offsetY, labelData.font, labelData.text, labelData.size, 1);
            }
            else
            {
                this.label = scene.add.text(labelData.offsetX, labelData.offsetY, labelData.text);
            }

            this.label.setOrigin(0, 0.5);

            this.add(this.label);

            if (labelData.inputEnabled)
            {
                this.label.setInteractive();
            }
        }

        this.currentState = null;

        this.updateButton((enabled) ? (checked) ? this.states.on : this.states.off : this.states.off);

        if (originX !== 0.5 || originY !== 0.5)
        {
            this.setOrigin(originX, originY);
        }

        scene.add.existing(this);

        //  Hit Area
        if (hitArea && hitAreaCallback)
        {
            this.button.setInteractive(hitArea, hitAreaCallback);
        }
        else if (radius > 0)
        {
            //  Quick circle creation
            this.button.setInteractive(new Phaser.Geom.Circle(this.button.width / 2, this.button.height / 2, radius), Phaser.Geom.Circle.Contains);
        }
        else
        {
            this.button.setInteractive();
        }

        this.button.input.alwaysEnabled = true;

        //  Single input key
        this.key = (key) ? key.toUpperCase() : null;

        //  Let's go ...
        this.setDisabled(!enabled);
    }

    /**
     * Internal function to handle updating the button, icon and label to the given state.
     *
     * @private
     * @param {*} state - The state to set the button to.
     * @returns
     * @memberof Toggle
     */
    updateButton (state)
    {
        if (this.currentState && state.type === this.currentState.type)
        {
            return;
        }

        this.currentState = state;

        const button = this.button;
        const icon = this.icon;
        const label = this.label;

        const buttonConfig = state.frame;
        const iconConfig = state.icon;
        const labelConfig = state.label;

        button.setFrame(buttonConfig.frame);

        button.setAngle(buttonConfig.angle);
        button.setScale(buttonConfig.scale, buttonConfig.scale);
        button.setPosition(buttonConfig.offsetX, buttonConfig.offsetY);

        button.setAlpha(buttonConfig.alpha);
        button.setTint(buttonConfig.tint[0], buttonConfig.tint[1], buttonConfig.tint[2], buttonConfig.tint[3]);

        if (icon && iconConfig)
        {
            if (iconConfig.frame === '')
            {
                //  There's no icon set for this state
                icon.setVisible(false);
            }
            else
            {
                icon.setFrame(iconConfig.frame);

                icon.setAngle(iconConfig.angle);
                icon.setScale(iconConfig.scale);
                icon.setPosition(iconConfig.offsetX, iconConfig.offsetY);
    
                icon.setAlpha(iconConfig.alpha);
                icon.setTint(iconConfig.tint[0], iconConfig.tint[1], iconConfig.tint[2], iconConfig.tint[3]);

                icon.setVisible(true);
            }
        }

        if (label && labelConfig)
        {
            label.setAngle(labelConfig.angle);
            label.setScale(labelConfig.scale);

            label.setAlpha(labelConfig.alpha);
            label.setTint(labelConfig.tint[0], labelConfig.tint[1], labelConfig.tint[2], labelConfig.tint[3]);

            if (labelConfig.isBitmapText)
            {
                label.setLetterSpacing(labelConfig.spacing);
                label.setFont(labelConfig.font, labelConfig.size, 1);
                label.fontSize = labelConfig.size;

                const align = labelConfig.align;

                if (align === 'left')
                {
                    label.setLeftAlign();
                }
                else if (align === 'center')
                {
                    label.setCenterAlign();
                }
                else if (align === 'right')
                {
                    label.setRightAlign();
                }
            }
            else
            {
                label.setFontFamily(labelConfig.font);
                label.setFontSize(labelConfig.size);
                label.setFontStyle(labelConfig.style);
                label.setColor(labelConfig.color);
                label.setAlign(labelConfig.align);

                const shadow = labelConfig.shadow;

                label.setShadow(shadow.offsetX, shadow.offsetY, shadow.color, shadow.blur, shadow.stroke, shadow.fill);
            }

            label.setText(labelConfig.text);

            const bx = button.displayOriginX;

            //  left alignment is likely the default one
            let tx = -(label.width + bx + labelConfig.offsetX);

            if (labelConfig.buttonAlign === 'right')
            {
                tx = bx + labelConfig.offsetX;
            }

            label.setPosition(tx, button.y + labelConfig.offsetY);
        }
    }

    /**
     * Internal function that builds a state configuration object for the given state, using the provided config.
     *
     * @private
     * @param {*} config - The config to extract the values from.
     * @param {*} state - The state to set the config into.
     * @returns
     * @memberof Toggle
     */
    setState (config, state)
    {
        //  If the config isn't an object, but a string, we default to the background frame name
        if (typeof config === 'string')
        {
            config = { frame: config };
        }

        //  -------------------------------------------------------------------------------------
        //  Frame
        //  -------------------------------------------------------------------------------------

        let target = state.frame;

        let configFrame = GetValue(config, 'frame');

        if (configFrame)
        {
            if (typeof configFrame === 'string')
            {
                configFrame = { frame: configFrame };
            }

            SetFrame(configFrame, target, '');
            SetTint(configFrame, target);
            SetTransform(configFrame, target);
        }
        else
        {
            state.frame = null;
        }

        //  -------------------------------------------------------------------------------------
        //  Icon
        //  -------------------------------------------------------------------------------------

        target = state.icon;

        let configIcon = GetValue(config, 'icon');

        const globalIcon = this.globals.icon;

        if (configIcon)
        {
            if (typeof configIcon === 'string')
            {
                configIcon = { frame: configIcon };
            }

            SetFrame(configIcon, target, globalIcon.frame);
            SetTint(configIcon, target, globalIcon.tint, globalIcon.alpha);
            SetTransform(configIcon, target, globalIcon.offsetX, globalIcon.offsetY, globalIcon.scale, globalIcon.angle);
        }
        else if (globalIcon)
        {
            SetFrame(globalIcon, target, globalIcon.frame);
            SetTint(globalIcon, target, globalIcon.tint, globalIcon.alpha);
            SetTransform(globalIcon, target);
        }
        else
        {
            state.icon = null;
        }

        //  -------------------------------------------------------------------------------------
        //  Label
        //  -------------------------------------------------------------------------------------

        target = state.label;

        let configLabel = GetValue(config, 'label');

        const globalLabel = this.globals.label;

        if (configLabel)
        {
            if (typeof configLabel === 'string')
            {
                configLabel = { text: configLabel };
            }

            state.custom = true;

            SetTint(configLabel, target, globalLabel.tint, globalLabel.alpha);
            SetTransform(configLabel, target, globalLabel.offsetX, globalLabel.offsetY, globalLabel.scale, globalLabel.angle);
            SetLabel(configLabel, target, globalLabel);
        }
        else if (globalLabel)
        {
            SetTint(globalLabel, target, globalLabel.tint, globalLabel.alpha);
            SetTransform(globalLabel, target);
            SetLabel(globalLabel, target, defaultLabel);
        }
        else
        {
            state.label = null;
        }

        //  -------------------------------------------------------------------------------------
        //  Sound
        //  -------------------------------------------------------------------------------------

        target = state.sound;

        let configSound = GetValue(config, 'sound');

        if (configSound)
        {
            if (typeof configSound === 'string')
            {
                configSound = { key: configSound };
            }

            if (!SetSound(configSound, target, defaultSound))
            {
                state.sound = null;
            }
        }
        else
        {
            state.sound = null;
        }

        return this;
    }

    /**
     * Internal function that sets the Out State from the defined config.
     *
     * @private
     * @param {*} config - The config to set to the state.
     * @returns
     * @memberof Toggle
     */
    setOnState (config)
    {
        this.setState(config, this.states.on);

        if (!this.states.on.frame)
        {
            console.error('Toggle missing on frame configuration');
        }

        return this;
    }

    /**
     * Internal function that sets the Over State from the defined config.
     *
     * @private
     * @param {*} config - The config to set to the state.
     * @returns
     * @memberof Toggle
     */
    setOffState (config)
    {
        return this.setState(config, this.states.off);
    }

    /**
     * Internal function that sets the Disabled State from the defined config.
     *
     * @private
     * @param {*} config - The config to set to the state.
     * @returns
     * @memberof Toggle
     */
    setDisabledState (config)
    {
        return this.setState(config, this.states.disabled);
    }

    /**
     * Start all the event listeners running on this component.
     * 
     * By default the button will listen for input events, but if you disable those, by calling
     * the `stop` method, then use this method to re-enable them again.
     *
     * @returns
     * @memberof Toggle
     */
    start ()
    {
        this.button.on('pointerdown', this.onDown, this);
        this.button.on('pointerup', this.onUp, this);
        this.button.on('pointerover', this.onOver, this);
        this.button.on('pointerout', this.onOut, this);

        if (this.key)
        {
            this.scene.input.keyboard.on('keydown-' + this.key, this.onKeyDown, this);
            this.scene.input.keyboard.on('keyup-' + this.key, this.onKeyUp, this);
        }

        if (this.label && this.label.input)
        {
            this.label.on('pointerdown', this.onKeyDown, this);
        }

        return this;
    }

    /**
     * Stops all the event listeners running on this component.
     * 
     * You can re-enable them again by calling the `start` method.
     *
     * @returns
     * @memberof Toggle
     */
    stop ()
    {
        this.button.off('pointerdown', this.onDown, this);
        this.button.off('pointerup', this.onUp, this);
        this.button.off('pointerover', this.onOver, this);
        this.button.off('pointerout', this.onOut, this);

        if (this.key)
        {
            this.scene.input.keyboard.off('keydown-' + this.key, this.onKeyDown, this);
            this.scene.input.keyboard.off('keyup-' + this.key, this.onKeyUp, this);
        }

        if (this.label && this.label.input)
        {
            this.label.off('pointerdown', this.onKeyDown, this);
        }

        return this;
    }

    /**
     * Internal key down handler.
     *
     * @private
     * @memberof Toggle
     */
    onKeyDown ()
    {
        if (this.enabled)
        {
            this.onDown({});
        }
    }

    /**
     * Internal key up handler.
     *
     * @private
     * @memberof Toggle
     */
    onKeyUp ()
    {
        if (this.enabled)
        {
            this.onUp({});
        }
    }

    /**
     * Sets the origin of this Button.
     * 
     * The values are given in the range 0 to 1.
     *
     * @param {number} [x=0.5] - The horizontal origin value.
     * @param {*} [y=x] - The vertical origin value.
     * @returns
     * @memberof Toggle
     */
    setOrigin (x = 0.5, y = x)
    {
        this.button.setOrigin(x, y);

        const stateIcon = this.currentState.icon;

        const cx = (this.button.displayWidth / 2);
        const cy = (this.button.displayHeight / 2);

        if (this.icon && stateIcon)
        {
            this.icon.setPosition(cx + stateIcon.offsetX, cy + stateIcon.offsetY);
        }

        const label = this.label;

        if (label)
        {
            const stateLabel = this.currentState.label;

            const bx = this.button.displayOriginX;

            //  left alignment is likely the default one
            let tx = -(label.width + bx + stateLabel.offsetX);
    
            if (stateLabel.buttonAlign === 'right')
            {
                tx = bx + stateLabel.offsetX;
            }
    
            label.setPosition(tx, this.button.y + stateLabel.offsetY);
        }

        return this;
    }

    /**
     * Set this button as being disabled or enabled.
     * 
     * A disabled button will show the disabled frame (if set) and will stop responding to input events.
     * 
     * Call this method with no arguments to re-enable the button and set it to the 'out' state.
     *
     * @param {boolean} [value=false] - Set to `true` to disable this button, or `false` to enable it.
     * @returns
     * @memberof Toggle
     */
    setDisabled (value = false)
    {
        if (value)
        {
            this.stop();

            this.updateButton(this.states.disabled);

            this.emit('disabled', this);

            this.enabled = false;
        }
        else
        {
            this.start();

            this.updateButton((this.checked) ? this.states.on : this.states.off);

            this.emit('enabled', this);

            this.enabled = true;
        }

        return this;
    }

    /**
     * The Sound to be played when the pointer moves over this Button.
     * 
     * The sound should already be loaded.
     * 
     * If using a marker, provide the marker details as an object, i.e.:
     * 
     * `{ name: 'marker name', start: 1, duration: 1.0, config: {} }`
     *
     * @param {?(ON|OFF|DISABLED)} [state] - The button state to set the sound for.
     * @param {string} [key] - The key of the audio file to play.
     * @param {Phaser.Types.Sound.SoundConfig} [config] - Optional sound configuration used when playing this sound.
     * @param {Phaser.Types.Sound.SoundMarker} [marker] - Optional marker object used to define a section of the audio file.
     * @returns
     * @memberof Toggle
     */
    setSound (state = null, key = '', config = null, marker = null)
    {
        if (typeof state === 'string')
        {
            state = this.stateConstMap[state];
        }

        const stateObj = this.statesMap[state];

        if (!stateObj.sound)
        {
            stateObj.sound = Phaser.Utils.Objects.Extend(true, {}, defaultSound);
        }

        const soundConfig = {
            key,
            config,
            marker
        };

        if (!SetSound(soundConfig, stateObj.sound))
        {
            stateObj.sound = null;
        }

        return this;
    }

    /**
     * Sets an additive tint on the background of this Button when in the given state.
     * 
     * This is a WebGL only feature.
     * 
     * The tint works by taking the pixel color values from the background texture, and then
     * multiplying it by the color value of the tint. You can provide one color value,
     * in which case the whole label will be tinted in that color. You can provide two color
     * values, in which case it will use them as a vertical gradient from top to bottom.
     * Or, you can provide a unique color per corner.
     * 
     * To modify the tint color once set, call this method again with new values.
     * To remove a tint, call this method without any color parameters given.
     *
     * @param {(ON|OFF|DISABLED)} [state=ON] - The state to apply the tint to.
     * @param {integer} [topLeft=0xffffff] - The tint being applied to the top-left of the label. If no other values are given this value is applied evenly, tinting the whole label.
     * @param {integer} [topRight] - The tint being applied to the top-right of the label. If this and a topLeft value are given, but no others, it's used as a vertical gradient.
     * @param {integer} [bottomLeft] - The tint being applied to the bottom-left of the label.
     * @param {integer} [bottomRight] - The tint being applied to the bottom-right of the label.
     * @returns
     * @memberof Toggle
     */
    setButtonTint (state = ON, topLeft = 0xffffff, topRight, bottomLeft, bottomRight)
    {
        if (typeof state === 'string')
        {
            state = this.stateConstMap[state];
        }

        this.updateTint(this.statesMap[state].frame, topLeft, topRight, bottomLeft, bottomRight);

        if (state === this.currentState.type)
        {
            const tint = this.statesMap[state].frame.tint;

            if (tint)
            {
                this.button.setTint(tint[0], tint[1], tint[2], tint[3]);
            }
        }

        return this;
    }

    /**
     * Sets an additive tint on the label of this Button when in the given state.
     * 
     * This is a WebGL only feature.
     * 
     * The tint works by taking the pixel color values from the Button label texture, and then
     * multiplying it by the color value of the tint. You can provide one color value,
     * in which case the whole label will be tinted in that color. You can provide two color
     * values, in which case it will use them as a vertical gradient from top to bottom.
     * Or, you can provide a unique color per corner.
     * 
     * To modify the tint color once set, call this method again with new values.
     * To remove a tint, call this method without any parameters given.
     *
     * @param {(ON|OFF|DISABLED)} [state=ON] - The state to apply the tint to.
     * @param {integer} [topLeft=0xffffff] - The tint being applied to the top-left of the label. If no other values are given this value is applied evenly, tinting the whole label.
     * @param {integer} [topRight] - The tint being applied to the top-right of the label. If this and a topLeft value are given, but no others, it's used as a vertical gradient.
     * @param {integer} [bottomLeft] - The tint being applied to the bottom-left of the label.
     * @param {integer} [bottomRight] - The tint being applied to the bottom-right of the label.
     * @returns
     * @memberof Toggle
     */
    setIconTint (state = ON, topLeft = 0xffffff, topRight, bottomLeft, bottomRight)
    {
        if (typeof state === 'string')
        {
            state = this.stateConstMap[state];
        }

        this.updateTint(this.statesMap[state].icon, topLeft, topRight, bottomLeft, bottomRight);

        if (state === this.currentState.type)
        {
            const tint = this.statesMap[state].icon.tint;

            if (tint)
            {
                this.icon.setTint(tint[0], tint[1], tint[2], tint[3]);
            }
        }

        return this;
    }

    /**
     * Sets an additive tint on the label of this Button when in thje given state.
     * 
     * This is a WebGL only feature.
     * 
     * The tint works by taking the pixel color values from the Button label texture, and then
     * multiplying it by the color value of the tint. You can provide one color value,
     * in which case the whole label will be tinted in that color. You can provide two color
     * values, in which case it will use them as a vertical gradient from top to bottom.
     * Or, you can provide a unique color per corner.
     * 
     * To modify the tint color once set, call this method again with new values.
     * To remove a tint, call this method without any parameters given.
     *
     * @param {(ON|OFF|DISABLED)} [state=ON] - The state to apply the tint to.
     * @param {integer} [topLeft=0xffffff] - The tint being applied to the top-left of the label. If no other values are given this value is applied evenly, tinting the whole label.
     * @param {integer} [topRight] - The tint being applied to the top-right of the label. If this and a topLeft value are given, but no others, it's used as a vertical gradient.
     * @param {integer} [bottomLeft] - The tint being applied to the bottom-left of the label.
     * @param {integer} [bottomRight] - The tint being applied to the bottom-right of the label.
     * @returns
     * @memberof Toggle
     */
    setLabelTint (state = ON, topLeft = 0xffffff, topRight, bottomLeft, bottomRight)
    {
        if (typeof state === 'string')
        {
            state = this.stateConstMap[state];
        }

        this.updateTint(this.statesMap[state].label, topLeft, topRight, bottomLeft, bottomRight);

        if (state === this.currentState.type)
        {
            const tint = this.statesMap[state].label.tint;

            if (tint)
            {
                this.label.setTint(tint[0], tint[1], tint[2], tint[3]);
            }
        }

        return this;
    }

    /**
     * Internal handler to update the tint on the given state.
     * 
     * @private
     * @param {*} target - The state to update.
     * @param {integer} [topLeft=0xffffff] - The tint being applied to the top-left of the label. If no other values are given this value is applied evenly, tinting the whole label.
     * @param {integer} [topRight] - The tint being applied to the top-right of the label. If this and a topLeft value are given, but no others, it's used as a vertical gradient.
     * @param {integer} [bottomLeft] - The tint being applied to the bottom-left of the label.
     * @param {integer} [bottomRight] - The tint being applied to the bottom-right of the label.
     * @returns
     * @memberof Toggle
     */
    updateTint (target, topLeft, topRight, bottomLeft, bottomRight)
    {
        if (target)
        {
            const tint = { tint: [ topLeft, topRight, bottomLeft, bottomRight ] };

            SetTint(tint, target);
        }

        return this;
    }

    /**
     * Change the text of the Label being displayed on this button, if any.
     * 
     * If no state is given, the label text will be updated across _all_ of the button states (up, down, out, etc).
     *
     * @param {string} [text=''] - The text to display on this Button. Only has any effect if a label was set in the constructor.
     * @param {?(ON|OFF|DISABLED)} [state] - The button state to set the label text to.
     * @returns
     * @memberof Toggle
     */
    setLabel (text = '', state = null)
    {
        if ((state === null || state === ON) && this.states.on.label)
        {
            this.states.on.label.text = text;
        }

        if ((state === null || state === OFF) && this.states.off.label)
        {
            this.states.off.label.text = text;
        }

        if ((state === null || state === DISABLED) && this.states.disabled.label)
        {
            this.states.disabled.label.text = text;
        }

        const label = this.label;

        if (label)
        {
            label.setText(text);
        }

        return this;
    }

    /**
     * Internal method to play the state sounds.
     *
     * @private
     * @param {*} sound - The sound object.
     * @memberof Toggle
     */
    playSound (state)
    {
        const sound = state.sound;

        if (sound)
        {
            this.scene.sound.play(sound.key, sound.config);
        }
    }

    /**
     * Internal method to handle the pointer event.
     * 
     * @private
     * @param {Phaser.Input.Pointer} pointer - The pointer that activated the event.
     * @memberof Toggle
     */
    onOver (pointer)
    {
        this.emit('pointerover', pointer, this);
    }

    /**
     * Internal method to handle the pointer event.
     * 
     * @private
     * @param {Phaser.Input.Pointer} pointer - The pointer that activated the event.
     * @memberof Toggle
     */
    onOut (pointer)
    {
        this.emit('pointerout', pointer, this);
    }

    /**
     * Internal method to handle the pointer event.
     * 
     * @private
     * @param {Phaser.Input.Pointer} pointer - The pointer that activated the event.
     * @memberof Toggle
     */
    onDown (pointer)
    {
        if (this.checked)
        {
            this.checked = false;

            this.updateButton(this.states.off);

            this.playSound(this.states.off);

            this.emit('off', this, pointer);
        }
        else
        {
            this.checked = true;

            this.updateButton(this.states.on);

            this.playSound(this.states.on);

            this.emit('on', this, pointer);
        }

        this.emit('toggle', this, this.checked, pointer);
    }

    /**
     * Internal method to handle the pointer event.
     * 
     * @private
     * @param {Phaser.Input.Pointer} pointer - The pointer that activated the event.
     * @memberof Toggle
     */
    onUp (pointer)
    {
        this.emit('pointerup', pointer, this);
    }

    /**
     * Internal destroy handler, called as part of the destroy process.
     *
     * @private
     * @memberof Toggle
     */
    preDestroy ()
    {
        this.stop();

        super.preDestroy();
    }

}
