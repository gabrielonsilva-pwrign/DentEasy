let currentTreatment = 'caries';

function initOdontogram(savedData = '{}') {
    const odontogram = document.getElementById('odontogram');
    if (!odontogram) {
        console.error('Container do odontograma não localizado');
        return;
    }

    const teethData = JSON.parse(savedData);

    const jaws = [
        {
            name: 'upper-jaw', quadrants: [
                { name: 'upper-right', range: [18, 17, 16, 15, 14, 13, 12, 11] },
                { name: 'upper-left', range: [21, 22, 23, 24, 25, 26, 27, 28] }
            ]
        },
        {
            name: 'lower-jaw', quadrants: [
                { name: 'lower-right', range: [48, 47, 46, 45, 44, 43, 42, 41] },
                { name: 'lower-left', range: [31, 32, 33, 34, 35, 36, 37, 38] }
            ]
        }
    ];

    jaws.forEach(jaw => {
        const jawDiv = document.createElement('div');
        jawDiv.className = `jaw ${jaw.name}`;

        jaw.quadrants.forEach(quadrant => {
            const quadrantDiv = document.createElement('div');
            quadrantDiv.className = `quadrant ${quadrant.name}`;

            quadrant.range.forEach(toothNumber => {
                const tooth = createTooth(toothNumber, teethData[toothNumber]);
                quadrantDiv.appendChild(tooth);
            });

            jawDiv.appendChild(quadrantDiv);
        });

        odontogram.appendChild(jawDiv);
    });

    // Add treatment selector
    const treatmentSelector = document.createElement('div');
    treatmentSelector.className = 'treatment-selector';
    treatmentSelector.innerHTML = `
        <label class="treatment-option">
            <input type="radio" name="treatment" value="caries" checked> Cárie
        </label>
        <label class="treatment-option">
            <input type="radio" name="treatment" value="restoration"> Restauração
        </label>
        <label class="treatment-option">
            <input type="radio" name="treatment" value="extraction"> Extração
        </label>
        <label class="treatment-option">
            <input type="radio" name="treatment" value="crown"> Coroa
        </label>
        <label class="treatment-option">
            <input type="radio" name="treatment" value="bridge"> Canal
        </label>
    `;
    odontogram.parentNode.insertBefore(treatmentSelector, odontogram);

    // Add event listener for treatment selection
    treatmentSelector.addEventListener('change', (e) => {
        if (e.target.name === 'treatment') {
            currentTreatment = e.target.value;
        }
    });

    // Add legend
    const legend = document.createElement('div');
    legend.className = 'treatment-legend';
    legend.innerHTML = `
        <div class="legend-item">
            <div class="legend-color" style="background-color: red;"></div>
            <span>Cárie</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: blue;"></div>
            <span>Restauração</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: black;"></div>
            <span>Extração</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: gold;"></div>
            <span>Coroa</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: purple;"></div>
            <span>Canal</span>
        </div>
    `;
    odontogram.parentNode.appendChild(legend);

    updateOdontogramData();
}

function createTooth(number, savedData = {}) {
    const tooth = document.createElement('div');
    tooth.className = 'tooth';
    tooth.dataset.toothNumber = number;
    tooth.innerHTML = `
        <span class="tooth-number">${number}</span>
        <div class="tooth-graphic">
            <div class="tooth-surface" data-surface="top-left"></div>
            <div class="tooth-surface" data-surface="top"></div>
            <div class="tooth-surface" data-surface="top-right"></div>
            <div class="tooth-surface" data-surface="left"></div>
            <div class="tooth-surface" data-surface="center"></div>
            <div class="tooth-surface" data-surface="right"></div>
            <div class="tooth-surface" data-surface="bottom-left"></div>
            <div class="tooth-surface" data-surface="bottom"></div>
            <div class="tooth-surface" data-surface="bottom-right"></div>
        </div>
    `;

    tooth.querySelectorAll('.tooth-surface').forEach(surface => {
        if (savedData[surface.dataset.surface]) {
            surface.classList.add(savedData[surface.dataset.surface]);
        }
        surface.addEventListener('click', (e) => toggleSurface(e.target));
    });

    return tooth;
}

function toggleSurface(surfaceElement) {
    if (surfaceElement) {
        const toothElement = surfaceElement.closest('.tooth');
        const toothNumber = toothElement.dataset.toothNumber;
        const surface = surfaceElement.dataset.surface;

        surfaceElement.classList.remove('caries', 'restoration', 'extraction', 'crown', 'bridge');
        if (currentTreatment) {
            surfaceElement.classList.add(currentTreatment);
        }
        updateOdontogramData();
    } else {
        console.error('Surface element not found');
    }
}

function updateOdontogramData() {
    const odontogramData = {};
    document.querySelectorAll('.tooth').forEach(tooth => {
        const toothNumber = tooth.dataset.toothNumber;
        odontogramData[toothNumber] = {};
        tooth.querySelectorAll('.tooth-surface').forEach(surface => {
            const treatment = ['caries', 'restoration', 'extraction', 'crown', 'bridge'].find(t => surface.classList.contains(t));
            if (treatment) {
                odontogramData[toothNumber][surface.dataset.surface] = treatment;
            }
        });
    });
    const odontogramDataInput = document.getElementById('odontogram_data');
    if (odontogramDataInput) {
        odontogramDataInput.value = JSON.stringify(odontogramData);
    } else {
        console.error('Odontogram data input not found');
    }
}

// Initialize the odontogram when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function () {
    const odontogramDataInput = document.getElementById('odontogram_data');
    const savedData = odontogramDataInput ? odontogramDataInput.value : '{}';
    initOdontogram(savedData);
});
