import {WowModelViewer} from './wow_model_viewer.js'
import {
    findRaceGenderOptions,
    optionsFromModel,
    getDisplaySlot,
    findItemsInEquipments,
    modelingType
} from "./character_modeling.js"

import "./setup.js"

/**
 *
 * @param aspect {number}: Size of the character
 * @param containerSelector {string}: jQuery selector on the container
 * @param model {{}|{id: number, type: number}}: A json representation of a character
 * @param env {('classic'|'live')}: select game enve
 * @returns {Promise<WowModelViewer>}
 */
async function generateModels(aspect, containerSelector, model, env=`live`) {
    let modelOptions
    let fullOptions
    if (model.id && model.type) {
        const {id, type} = model
        modelOptions = {models: {id, type}}
    } else {
        const {race, gender} = model

        // CHARACTER OPTIONS
        // This is how we describe a character properties
        fullOptions = await findRaceGenderOptions(
            race,
            gender
        )
        modelOptions = optionsFromModel(model, fullOptions)
    }
    if(env === `classic`) {
        modelOptions = {
            dataEnv: `classic`,
            env: `classic`,
            gameDataEnv: `classic`,
            hd: false,
            ...modelOptions
        }
    } else {
        modelOptions = {
            hd: true,
            ...modelOptions
        }
    }
    const models = {
        type: 2,
        contentPath: window.CONTENT_PATH,
        // eslint-disable-next-line no-undef
        container: jQuery(containerSelector),
        aspect: aspect,
        ...modelOptions
    }
    console.log(`Creating viewer with options`, models)

    // eslint-disable-next-line no-undef
    const wowModelViewer =  await new WowModelViewer(models)
    if(fullOptions) {
        wowModelViewer.currentCharacterOptions = fullOptions
        wowModelViewer.characterGender = model.gender
        wowModelViewer.characterRace = model.race

    }
    return wowModelViewer
}

const buttonF = document.querySelector('#show3DModelFast');
const buttonD = document.querySelector('#show3DModelDetailed');
const placeholder = document.querySelector('#placeholder');

const removeButtons = () => {
    buttonD.style.display = 'none';
    buttonF.style.display = 'none';
    $('#patch').hide();
    placeholder.classList.add("animate-flicker");
    return Promise.resolve();
};

const removePlaceholder = () => {
    placeholder.style.display = 'none';
    return Promise.resolve();
};

const showAnimationControl = async () => {
    await new Promise(resolve => setTimeout(resolve, 5000));

    model.getListAnimations().forEach(value => {
        $('#animationSelect').append($('<option>', { value }).text(value));
    });

    $("#patch").show();
    $("div.animation-dropdown").show();

    $(document).on('click', '#playAnim', () => {
        $("#playAnim").hide();
        $("#pauseAnim").show();
        model.setAnimPaused(false);
    });

    $(document).on('click', '#pauseAnim', () => {
        $("#pauseAnim").hide();
        $("#playAnim").show();
        model.setAnimPaused(true);
    });
};

const show3DModel = async (type) => {
    await removeButtons();
    if (type === 1) {
        window.model = await generateModels(1, '#model3D', character);
    } else {
        const items = await findItemsInEquipments(equipments);
        character.items = items;
        window.model = await generateModels(1, '#model3D', character);
    }
    await removePlaceholder();
    await showAnimationControl();
};

window.resetView = () => {
    const model3DDiv = document.querySelector('#model3D');
    model3DDiv.innerHTML = '';
    model3DDiv.style = '';

    buttonD.style.display = 'inline-block';
    buttonF.style.display = 'inline-block';
    $('#patch').show();

    placeholder.style.display = 'block';
    placeholder.style.opacity = 0;

    // Use a small timeout to trigger the fade-in effect
    setTimeout(() => {
        placeholder.style.transition = 'opacity 1s';
        placeholder.style.opacity = 1;
    }, 10); 

    placeholder.classList.remove("animate-flicker");
    $("div.animation-dropdown").hide();

    $('#animationSelect').empty().append($('<option>', {
        value: '',
        text: 'Choose animation',
        selected: true,
        disabled: true,
        hidden: true
    }));
};

// Event listeners for the buttons
buttonF.addEventListener('click', () => show3DModel(1));
buttonD.addEventListener('click', () => show3DModel(2));

export {
    findRaceGenderOptions,
    generateModels,
    getDisplaySlot,
    findItemsInEquipments,
    modelingType
}
