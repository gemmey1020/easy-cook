const fromInfo = {};

// button Suivant & precedent
const formBtn1 = document.querySelector("#btn-1")

const formBtnPrev2 = document.querySelector("#btn-2-prev")
const formBtnNext2 = document.querySelector("#btn-2-next")

const formBtnPrev3 = document.querySelector("#btn-3-prev")
const formBtnNext3 = document.querySelector("#btn-3-next")

const formBtnPrev4 = document.querySelector("#btn-4-prev")
const formBtnNext4 = document.querySelector("#btn-4-next")

const formBtnPrev5 = document.querySelector("#btn-5-prev")
const formBtnNext5 = document.querySelector("#btn-5-next")

const formBtnPrev6 = document.querySelector("#btn-6-prev")
const formBtnNext6 = document.querySelector("#btn-6-next")

const formBtnPrev7 = document.querySelector("#btn-7-prev")
const formBtnNext7 = document.querySelector("#btn-7-next")

const formBtnPrev8 = document.querySelector("#btn-8-prev")


// Field selection genre

const formgenreh = document.querySelector("#checkgenre_homme")
const formgenref = document.querySelector("#checkgenre_femme")


// Field selection objectif

const formobje = document.querySelector("#check_obj_e")
const formobjs = document.querySelector("#check_obj_s")
const formobjm = document.querySelector("#check_obj_m")
const formobjb = document.querySelector("#check_obj_b")


// Field selection frequence 1

const formfrq0 = document.querySelector("#check_frequence_0")
const formfrq13 = document.querySelector("#check_frequence_13")
const formfrq45 = document.querySelector("#check_frequence_45")
const formfrq67 = document.querySelector("#check_frequence_67")

const formkcal = document.querySelector("#inkcal")

// Field selection frequence 2
const formfrq1 = document.querySelector("#check_frequence_1")
const formfrq2 = document.querySelector("#check_frequence_2")

const formsx = document.querySelector("#base_container").innerHTML




const formpoid = document.querySelector("#poid")
const formtaille = document.querySelector("#taille")
const formdate = document.querySelector("#date")

const nomInput = document.getElementById("nom");
const prenomInput = document.getElementById("prenom");
const mailInput = document.getElementById("mail");
const mobileInput = document.getElementById("mobile");
const villeInput = document.getElementById("ville");

// Buttons functions :

// Button listener of form 1
formBtn1.addEventListener("click", function (e) {

    gotoNextForm(formBtn1, formBtnNext2, 1, 2)
    e.preventDefault()


})

// Next button listener of form 2
formBtnNext2.addEventListener("click", function (e) {

    gotoNextForm(formBtnNext2, formBtnNext3, 2, 3)
    e.preventDefault()

})

// Previous button listener of form 2
formBtnPrev2.addEventListener("click", function (e) {
    gotoNextForm(formBtnNext2, formBtn1, 2, 1)
    e.preventDefault()
    step = 2
    document.querySelector(`.step--${step}`).classList.remove("step-active")
})


// Previous button listener of form 3
formBtnNext3.addEventListener("click", function (e) {
    gotoNextForm(formBtnNext3, formBtnNext4, 3, 4)
    this.scrollTop = 0;
    e.preventDefault()
})

// Previous button listener of form 3
formBtnPrev3.addEventListener("click", function (e) {
    gotoNextForm(formBtnNext3, formBtnNext2, 3, 2)
    e.preventDefault()
    step = 3
    document.querySelector(`.step--${step}`).classList.remove("step-active")
})

// Previous button listener of form 4
formBtnNext4.addEventListener("click", function (e) {
    calculekcal()
    gotoNextForm(formBtnNext4, formBtnNext5, 4, 5)
    e.preventDefault()
})
// Previous button listener of form 4
formBtnPrev4.addEventListener("click", function (e) {
    gotoNextForm(formBtnNext4, formBtnNext3, 4, 3)
    e.preventDefault()
    step = 4
    document.querySelector(`.step--${step}`).classList.remove("step-active")
})

// Previous button listener of form 5
formBtnNext5.addEventListener("click", function (e) {
    if (document.getElementById('kcal').value) {
        fromInfo.kcal = document.getElementById('kcal').value;
    }
    gotoNextForm(formBtnNext5, formBtnNext6, 5, 6)
    e.preventDefault()
})
// Previous button listener of form 5
formBtnPrev5.addEventListener("click", function (e) {
    gotoNextForm(formBtnNext5, formBtnNext4, 5, 4)
    e.preventDefault()
    step = 5
    document.querySelector(`.step--${step}`).classList.remove("step-active")
})

// Previous button listener of form 6
formBtnNext6.addEventListener("click", function (e) {
    gotoNextForm(formBtnNext6, formBtnNext7, 6, 7);
    //document.getElementById('kcal-jour').textContent = 'Vous avez besoin de ' + fromInfo.kcal + ' kcal/jour';
    //document.getElementById('kcal-consommer').textContent = 'Il vous restera à consommer chez vous ' + fromInfo.kcal + ' kcal/jour';
    e.preventDefault()
})

// Previous button listener of form 6
formBtnPrev6.addEventListener("click", function (e) {
    gotoNextForm(formBtnNext6, formBtnNext5, 6, 5)
    e.preventDefault()
    step = 6
    document.querySelector(`.step--${step}`).classList.remove("step-active")
})

formBtnPrev7.addEventListener("click", function (e) {
    gotoNextForm(formBtnNext7, formBtnNext6, 7, 6)
    e.preventDefault()
    step = 7
    document.querySelector(`.step--${step}`).classList.remove("step-active")
})


const gotoNextForm = (prev, next, stepPrev, stepNext) => {
    // Get form through the button
    const prevForm = prev.parentElement
    const nextForm = next.parentElement
    const nextStep = document.querySelector(`.step--${stepNext}`)
    const prevStep = document.querySelector(`.step--${stepPrev}`)
    // Add active/inactive classes to both previous and next form
    nextForm.classList.add("form-active")
    nextForm.classList.add("form-active-animate")
    prevForm.classList.add("form-inactive")
    // Change the active step element
    // prevStep.classList.remove("step-active")
    nextStep.classList.add("step-active")
    // Remove active/inactive classes to both previous an next form
    setTimeout(() => {
        prevForm.classList.remove("form-active")
        prevForm.classList.remove("form-inactive")
        nextForm.classList.remove("form-active-animate")
    }, 99999999999999999999999999999999999999999999999*9)
}

// selection fields functions :

// Button genre homme form 1
formgenreh.addEventListener("click", function (e) {

    document.querySelector(`#checkgenre_homme`).classList.add("form_check_active")
    document.querySelector(`#checkgenre_femme`).classList.remove("form_check_active")

    document.getElementById('genre').value = 'Male';
    console.log('form1', document.getElementById('genre').value);
    fromInfo.genre = 'Male';


    document.querySelector('#f').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#h').classList.add("cardTextSelected___19-wV")
    document.getElementById("icon_h").src = "/assets/online-assessments/static/hh.svg";
    document.getElementById("icon_f").src = "/assets/online-assessments/static/f.svg";
    const button = document.querySelector('#btn-1').disabled = false;

})
// Button genre femme form 1
formgenref.addEventListener("click", function (e) {

    document.querySelector(`#checkgenre_homme`).classList.remove("form_check_active")
    document.querySelector(`#checkgenre_femme`).classList.add("form_check_active")

    document.getElementById('genre').value = 'Female';
    console.log('form1', document.getElementById('genre').value);
    fromInfo.genre = 'Female';

    document.querySelector('#h').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#f').classList.add("cardTextSelected___19-wV")
    document.getElementById("icon_f").src = "/assets/online-assessments/static/ff.svg";
    document.getElementById("icon_h").src = "/assets/online-assessments/static/h.svg";

    const button = document.querySelector('#btn-1').disabled = false;

})


// Button objectifs form 2
formobje.addEventListener("click", function (e) {
    document.querySelector(`#check_obj_e`).classList.add("form_check_active")
    document.querySelector(`#check_obj_s`).classList.remove("form_check_active")
    document.querySelector(`#check_obj_m`).classList.remove("form_check_active")
    document.querySelector(`#check_obj_b`).classList.remove("form_check_active")
    document.getElementById('objectif').value = 'Perte de poids';
    fromInfo.objectif = 'Perte de poids';
    document.querySelector('#s').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#m').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#b').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#e').classList.add("cardTextSelected___19-wV")
    document.getElementById("icon_e").src = "/assets/online-assessments/static/ee.svg";
    document.getElementById("icon_s").src = "/assets/online-assessments/static/ss.svg";
    document.getElementById("icon_m").src = "/assets/online-assessments/static/mm.svg";
    document.getElementById("icon_b").src = "/assets/online-assessments/static/bb.svg";
    const button = document.querySelector('#btn-2-next').disabled = false;
})
// Button objectifs form 2
formobjs.addEventListener("click", function (e) {
    document.querySelector(`#check_obj_s`).classList.add("form_check_active")
    document.querySelector(`#check_obj_e`).classList.remove("form_check_active")
    document.querySelector(`#check_obj_m`).classList.remove("form_check_active")
    document.querySelector(`#check_obj_b`).classList.remove("form_check_active")
    document.getElementById('objectif').value = 'Sèche musculaire';
    fromInfo.objectif = 'Sèche musculaire';
    document.querySelector('#e').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#m').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#b').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#s').classList.add("cardTextSelected___19-wV")
    document.getElementById("icon_e").src = "/assets/online-assessments/static/e.svg";
    document.getElementById("icon_s").src = "/assets/online-assessments/static/ss.svg";
    document.getElementById("icon_m").src = "/assets/online-assessments/static/m.svg";
    document.getElementById("icon_b").src = "/assets/online-assessments/static/b.svg";
    const button = document.querySelector('#btn-2-next').disabled = false;
})
// Button objectifs form 2
formobjm.addEventListener("click", function (e) {
    document.querySelector(`#check_obj_m`).classList.add("form_check_active")
    document.querySelector(`#check_obj_e`).classList.remove("form_check_active")
    document.querySelector(`#check_obj_s`).classList.remove("form_check_active")
    document.querySelector(`#check_obj_b`).classList.remove("form_check_active")
    document.getElementById('objectif').value = "Prise de masse";
    fromInfo.objectif = 'Prise de masse';

    document.querySelector('#s').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#e').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#b').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#m').classList.add("cardTextSelected___19-wV")
    document.getElementById("icon_e").src = "/assets/online-assessments/static/e.svg";
    document.getElementById("icon_s").src = "/assets/online-assessments/static/s.svg";
    document.getElementById("icon_m").src = "/assets/online-assessments/static/mm.svg";
    document.getElementById("icon_b").src = "/assets/online-assessments/static/b.svg";
    const button = document.querySelector('#btn-2-next').disabled = false;
})
// Button objectifs form 2
formobjb.addEventListener("click", function (e) {
    document.querySelector(`#check_obj_b`).classList.add("form_check_active")
    document.querySelector(`#check_obj_e`).classList.remove("form_check_active")
    document.querySelector(`#check_obj_m`).classList.remove("form_check_active")
    document.querySelector(`#check_obj_s`).classList.remove("form_check_active")
    document.getElementById('objectif').value = "Bien être";
    fromInfo.objectif = 'Bien être';

    document.querySelector('#e').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#m').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#s').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#b').classList.add("cardTextSelected___19-wV")
    document.getElementById("icon_e").src = "/assets/online-assessments/static/e.svg";
    document.getElementById("icon_s").src = "/assets/online-assessments/static/s.svg";
    document.getElementById("icon_m").src = "/assets/online-assessments/static/m.svg";
    document.getElementById("icon_b").src = "/assets/online-assessments/static/bb.svg";
    const button = document.querySelector('#btn-2-next').disabled = false;
})

function convertdate(date) {
    var date = date;
    var [year, month, day] = date.split("-");
    var newDate = `${day}-${month}-${year}`

    return newDate;
  }

  function dateIsValid(date) {
    date = new Date(convertdate(date))
    return True;
}

// button poid&taille form 3
formpoid.addEventListener("change", function (e) {
    var poid = document.getElementById('poid').value;
    var date = document.getElementById('date').value;
    var taille = document.getElementById('taille').value;
    date = new Date(convertdate(date))
    fromInfo.poids = poid;
    fromInfo.date_naissance_client = date;
    fromInfo.taille = taille;

    if ((poid <= 500) && (poid >= 30)) {
        document.querySelector('#check_p').classList.add("form_check_active")
        document.getElementById("icon_p").src = "/assets/online-assessments/static/pp.svg";
    } else {
        document.querySelector('#check_p').classList.remove("form_check_active")
        document.getElementById("icon_p").src = "/assets/online-assessments/static/p.svg";
    }

    if (((poid <= 500) && (poid >= 30)) && ((taille >= 30) && ((taille <= 300) && (date)))) {
        const button = document.querySelector('#btn-3-next').disabled = false;
    } else {
        const button = document.querySelector('#btn-3-next').disabled = true;
    }
})
// button poid&taille form 3
formtaille.addEventListener("change", function (e) {
    var poid = document.getElementById('poid').value;
    var date = document.getElementById('date').value;
    var taille = document.getElementById('taille').value;
    fromInfo.poids = poid;
    fromInfo.date_naissance_client = date;
    fromInfo.taille = taille;

    if ((taille >= 30) && (taille <= 300)) {
        document.querySelector('#check_t').classList.add("form_check_active")
        document.getElementById("icon_t").src = "/assets/online-assessments/static/tt.svg";
    } else {
        document.querySelector('#check_t').classList.remove("form_check_active")
        document.getElementById("icon_t").src = "/assets/online-assessments/static/t.svg";
    }


    if (((poid <= 500) && (poid >= 30)) && ((taille >= 30) && ((taille <= 300) && (date)))) {
        const button = document.querySelector('#btn-3-next').disabled = false;
    } else {
        const button = document.querySelector('#btn-3-next').disabled = true;
    }

})


// button poid&taille form 3
formdate.addEventListener("change", function (e) {
    var date = document.getElementById('date').value;
    var poid = document.getElementById('poid').value;
    var taille = document.getElementById('taille').value;
    fromInfo.poids = poid;
    fromInfo.date_naissance_client = date;
    fromInfo.taille = taille;

    if ((date)) {
        document.querySelector('#check_d').classList.add("form_check_active")
        document.getElementById("icon_d").src = "/assets/online-assessments/static/dd.svg";
    } else {
        document.querySelector('#check_d').classList.remove("form_check_active")
        document.getElementById("icon_d").src = "/assets/online-assessments/static/d.svg";
    }

    if (((poid <= 500) && (poid >= 30)) && ((taille >= 30) && ((taille <= 300) && (date)))) {
        const button = document.querySelector('#btn-3-next').disabled = false;
    } else {
        const button = document.querySelector('#btn-3-next').disabled = true;
    }
})


// Button frequences form 4
formfrq0.addEventListener("click", function (e) {
    document.querySelector(`#check_frequence_0`).classList.add("form_check_active")
    document.querySelector(`#check_frequence_13`).classList.remove("form_check_active")
    document.querySelector(`#check_frequence_45`).classList.remove("form_check_active")
    document.querySelector(`#check_frequence_67`).classList.remove("form_check_active")
    document.getElementById('frequence1').value = "Sédentaire";
    fromInfo.frequence_sportif = 'Sédentaire';


    document.querySelector('#f0').classList.add("cardTextSelected___19-wV")
    document.querySelector('#f13').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#f45').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#f67').classList.remove("cardTextSelected___19-wV")
    document.getElementById("icon_f0").src = "/assets/online-assessments/static/ff0.svg";
    document.getElementById("icon_f13").src = "/assets/online-assessments/static/f13.svg";
    document.getElementById("icon_f45").src = "/assets/online-assessments/static/f45.svg";
    document.getElementById("icon_f67").src = "/assets/online-assessments/static/f67.svg";
    const button = document.querySelector('#btn-4-next').disabled = false;
})
// Button frequences form 4
formfrq13.addEventListener("click", function (e) {
    document.querySelector(`#check_frequence_13`).classList.add("form_check_active")
    document.querySelector(`#check_frequence_0`).classList.remove("form_check_active")
    document.querySelector(`#check_frequence_45`).classList.remove("form_check_active")
    document.querySelector(`#check_frequence_67`).classList.remove("form_check_active")
    document.getElementById('frequence1').value = "Modérément actif";
    fromInfo.frequence_sportif = 'Modérément actif';

    document.querySelector('#f0').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#f13').classList.add("cardTextSelected___19-wV")
    document.querySelector('#f45').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#f67').classList.remove("cardTextSelected___19-wV")
    document.getElementById("icon_f0").src = "/assets/online-assessments/static/f0.svg";
    document.getElementById("icon_f13").src = "/assets/online-assessments/static/ff13.svg";
    document.getElementById("icon_f45").src = "/assets/online-assessments/static/f45.svg";
    document.getElementById("icon_f67").src = "/assets/online-assessments/static/f67.svg";
    const button = document.querySelector('#btn-4-next').disabled = false;
})
// Button frequences form 4
formfrq45.addEventListener("click", function (e) {
    document.querySelector(`#check_frequence_45`).classList.add("form_check_active")
    document.querySelector(`#check_frequence_0`).classList.remove("form_check_active")
    document.querySelector(`#check_frequence_13`).classList.remove("form_check_active")
    document.querySelector(`#check_frequence_67`).classList.remove("form_check_active")
    document.getElementById('frequence1').value = "Actif";
    fromInfo.frequence_sportif = 'Actif';

    document.querySelector('#f0').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#f13').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#f45').classList.add("cardTextSelected___19-wV")
    document.querySelector('#f67').classList.remove("cardTextSelected___19-wV")
    document.getElementById("icon_f0").src = "/assets/online-assessments/static/f0.svg";
    document.getElementById("icon_f13").src = "/assets/online-assessments/static/f13.svg";
    document.getElementById("icon_f45").src = "/assets/online-assessments/static/ff45.svg";
    document.getElementById("icon_f67").src = "/assets/online-assessments/static/f67.svg";
    const button = document.querySelector('#btn-4-next').disabled = false;
})
// Button frequences form 4
formfrq67.addEventListener("click", function (e) {
    document.querySelector(`#check_frequence_67`).classList.add("form_check_active")
    document.querySelector(`#check_frequence_0`).classList.remove("form_check_active")
    document.querySelector(`#check_frequence_13`).classList.remove("form_check_active")
    document.querySelector(`#check_frequence_45`).classList.remove("form_check_active")
    document.getElementById('frequence1').value = "Très actif";
    fromInfo.frequence_sportif = 'Très actif';

    document.querySelector('#f0').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#f13').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#f45').classList.remove("cardTextSelected___19-wV")
    document.querySelector('#f67').classList.add("cardTextSelected___19-wV")
    document.getElementById("icon_f0").src = "/assets/online-assessments/static/f0.svg";
    document.getElementById("icon_f13").src = "/assets/online-assessments/static/f13.svg";
    document.getElementById("icon_f45").src = "/assets/online-assessments/static/f45.svg";
    document.getElementById("icon_f67").src = "/assets/online-assessments/static/ff67.svg";
    const button = document.querySelector('#btn-4-next').disabled = false;
})
function calculeage(date){
    var today = new Date().getFullYear();
    var date = date;
    var [year, month, day] = date.split("-");
    var newDate = `${day}-${month}-${year}`
    var year = `${year}`
    var age =  today - year

    return age
}

// // Button Form5
function calculekcal(){
    var genre = document.getElementById('genre').value;
    var date = document.getElementById('date').value;
    var age = calculeage(date);
    var poid = document.getElementById('poid').value;
    var taille = document.getElementById('taille').value;
    var objectif = document.getElementById('objectif').value;
    var freq1 = document.getElementById('frequence1').value;
    console.log('taille', taille)
    console.log('poid', poid)
    console.log('age', age)
    //get objectif value Metabloisme F&H
    if ( objectif == 'Perte de poids'){
        var val_obj = 0.75;
    }

    if (objectif == 'Sèche musculaire'){
        var val_obj = 0.85;
    }
    if (objectif == 'Prise de masse'){
        var val_obj = 1.1;
    }
    if( objectif == 'Bien être'){
        var val_obj = 1;
    }
    console.log('objectif', val_obj)
    //Calccule Metabloisme F&H
    if (genre == 'Male'){
        var Metabloisme = (10 * poid)+(6.25 * taille)-(5 * age)+5;
    }
    if (genre == 'Female'){
        var Metabloisme = (10 * poid)+(6.25 * taille)-(5 * age)-161;
    }
    console.log('Metabloisme', Metabloisme)
    if ((objectif == 'Perte de poids') || (objectif == 'Sèche musculaire')){
        if (freq1 == 'Sédentaire') {
            var val_f1 = 1.2;
            if (genre == 'Male'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
            if (genre == 'Female'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
        }
        if (freq1 == 'Modérément actif'){
            var val_f1 = 1.375;
            if (genre == 'Male'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
            if (genre == 'Female'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
        }
        if (freq1 == 'Actif'){
            var val_f1 = 1.4;
            if (genre == 'Male'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
            if (genre == 'Female'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
        }
        if (freq1 == 'Très actif'){
            var val_f1 = 1.7;
            if (genre == 'Male'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
            if (genre == 'Female'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
        }
        console.log('activity', val_f1)
        console.log('Maintien', Maintien)
        console.log('Intake', Intake)
        console.log('prot', tot1)
        console.log('gluc', tot2)
        console.log('lipi', tot3)
    }

    if ((objectif == 'Prise de masse') || (objectif == 'Bien être')){
        if (freq1 == 'Sédentaire'){
            var val_f1 = 1.2;
            if (genre == 'Male'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
            if (genre == 'Female'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
        }
        if (freq1 == 'Modérément actif'){
            var val_f1 = 1.4;
            if (genre == 'Male'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
            if (genre == 'Female'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
        }
        if (freq1 == 'Actif'){
            var val_f1 = 1.55;
            if (genre == 'Male'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
            if (genre == 'Female'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
        }
        if (freq1 ==  'Très actif'){
            var val_f1 = 1.725;
            if (genre == 'Male'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
            if (genre == 'Female'){
                var Maintien = Metabloisme * val_f1;
                var Intake = Maintien * val_obj;
                var tot1 = poid * 2;
                var tot3 = poid;
                var tot2 = Math.round((Intake - (tot1 * 4) - (tot3 * 9))/4);
            }
        }
    }
    // POst Values

    fromInfo.nb_calories = Math.round(Intake/50)*50;
    fromInfo.proteine = Math.round(tot1);
    fromInfo.glucide = Math.round(tot2);
    fromInfo.lipide = Math.round(tot3);
    fromInfo.age = age

    // document.getElementById('total').value = Math.round(Intake);
    // document.getElementById('total_p').value = Math.round(tot1);
    // document.getElementById('total_g').value = tot2;
    // document.getElementById('total_l').value = Math.round(tot3);

    document.getElementById('span_total').innerHTML = Math.round(Intake/50)*50;
    document.getElementById('span_total_p').innerHTML = Math.round(tot1);
    document.getElementById('span_total_g').innerHTML = Math.round(tot2);
    document.getElementById('span_total_l').innerHTML = Math.round(tot3);

}

function calculekcal2(){
    var poid = document.getElementById('poid').value;
    var kcal = document.getElementById('kcal').value;

    // document.getElementById('total_p').value = Math.round(poid * 2);
    // document.getElementById('total_l').value = Math.round(poid);
    // document.getElementById('total_g').value = Math.round((kcal - (proteines * 4) - (lipides * 9))/4);
    fromInfo.lipide = Math.round(poid);
    fromInfo.proteine = Math.round(poid * 2);
    fromInfo.glucide = Math.round((kcal - (fromInfo.proteine * 4) - (fromInfo.lipide * 9))/4);


    document.getElementById('span_total_p').innerHTML = fromInfo.proteine;
    document.getElementById('span_total_g').innerHTML = fromInfo.glucide;
    document.getElementById('span_total_l').innerHTML = fromInfo.lipide;
}

// Button frequences form 5
formkcal.addEventListener("change", function (e) {
    var kcal = document.getElementById('kcal').value;
    if (kcal > 0) {
        document.querySelector('#inkcal').classList.add("form_check_active");
        calculekcal2();
    } else {
        document.querySelector('#inkcal').classList.remove("form_check_active") ;
        calculekcal();
    }
    fromInfo.kcal = kcal;
})


// Button frequences form 6
formfrq1.addEventListener("click", function (e) {
    document.querySelector(`#check_frequence_1`).classList.add("form_check_active")
    document.querySelector(`#check_frequence_2`).classList.remove("form_check_active")
    document.getElementById('frequence2').value = "sur place"
    fromInfo.type_consultation = 'sur place';
    const button = document.querySelector('#btn-6-next').disabled = false;
    console.log(fromInfo.type_consultation)

})
// Button frequences form 6
formfrq2.addEventListener("click", function (e) {
    document.querySelector(`#check_frequence_2`).classList.add("form_check_active")
    document.querySelector(`#check_frequence_1`).classList.remove("form_check_active")
    document.getElementById('frequence2').value = "par tel"
    fromInfo.type_consultation = 'par tel';
    const button = document.querySelector('#btn-6-next').disabled = false;
    console.log(fromInfo.type_consultation)
})

function Affichage_Message(rec){
    console.log('function fenetre message !')

    document.querySelector(`#container__stepper`).classList.remove("message_formhidden_true");
    document.querySelector(`#container_last`).classList.remove("message_formhidden_true");

    document.querySelector(`#container__stepper`).classList.add("message_formhidden_true");
    document.querySelector(`#container_last`).classList.add("message_formhidden_true");

    document.getElementById('base_container').innerHTML = '<div class="container_msg" style="margin-top: 63px;"id="container_msg_id"><div class="container___1fUfH"><div class="infoForm"><div class="form_check_body_e"><span style="text-align: center;"><h1 class="text_msg" id="msg"></h1></span><div id="icon_iddiv" style="text-align: center;"></div></div></div></div></div><div id="div_button"></div>';
    document.getElementById('msg').innerHTML = rec.msg;

    if (rec.id == false){
        document.getElementById('div_button').innerHTML = '<a href="/home/shazler/Bureau/yass/eat&fit/App/index.html"><button class="form__btn_ret button___1_PFv" id="btn-8-prev">Précédent</button></a>';
        document.getElementById('icon_iddiv').innerHTML = '<img src="/assets/online-assessments/static/non.png" style="width: 20%;margin-top: 1%;">';
    }else{
        document.getElementById('icon_iddiv').innerHTML = '<img src="/assets/online-assessments/static/yes.png" style="width: 70px;margin-top: 1%;">';
    }

}

formBtnNext7.addEventListener("click", function (e) {
    var nom = document.getElementById('nom').value;
    var prenom = document.getElementById('prenom').value;
    var mail = document.getElementById('mail').value;
    var mobile = document.getElementById('mobile').value;
    var ville = document.getElementById('ville').value;


    fromInfo.nom = nom;
    fromInfo.prenom = prenom;
    fromInfo.email = mail;
    fromInfo.phone = mobile;
    fromInfo.ville = ville;
    if (!nom) {
        document.getElementById('nom').classList.add("required-input");
        document.getElementById('span_nom').style.visibility = 'visible';

    }
    if (!prenom) {
        document.getElementById('prenom').classList.add("required-input");
        document.getElementById('span_prenom').style.visibility = 'visible';
    }
    if (!mail) {
        document.getElementById('mail').classList.add("required-input");
        document.getElementById('span_mail').style.visibility = 'visible';
    }
    if (!mobile) {
        document.getElementById('mobile').classList.add("required-input");
        document.getElementById('span_mobile').style.visibility = 'visible';
    }
    if (!ville) {
        document.getElementById('ville').classList.add("required-input");
        document.getElementById('span_ville').style.visibility = 'visible';
    }
    if ((nom) && (prenom) && (mail) && (mobile) && (ville)) {
        // https://hello.eatfit.tn/api/wizard/
        //http://192.168.1.88:8012/api/wizard/
        $.ajax({
            type: "POST",
            url: "https://hello.eatfit.tn/api/wizard/",
            data: fromInfo,
            success: function(msg){
                // response = JSON.stringify(msg);
                rec = JSON.parse(msg)
                console.log("msg", msg)
                console.log("rec", rec)
                if(rec[0]['id'] != false) {
                    Affichage_Message(rec[0]);
                    console.log('1 msg :', rec[0]['msg'] )
                }else {
                    console.log('2 msg :', rec )
                    console.log(' msg num :', rec[0]['msg'] )
                    document.getElementById('span_mobile3').style.visibility = 'visible';
                }
            }
        });
  }
})

nomInput.addEventListener("change", function (e) {
    if (/\S/.test(document.getElementById('nom').value)) {
        document.getElementById('nom').classList.remove("required-input");
        document.getElementById('span_nom').style.visibility = 'hidden';
    } else {
        document.getElementById('nom').classList.add("required-input");
        document.getElementById('span_nom').style.visibility = 'visible';
    }
});
prenomInput.addEventListener("change", function (e) {
    if (/\S/.test(document.getElementById('prenom').value)) {
        document.getElementById('prenom').classList.remove("required-input");
        document.getElementById('span_prenom').style.visibility = 'hidden';
    } else {
        document.getElementById('prenom').classList.add("required-input");
        document.getElementById('span_prenom').style.visibility = 'visible';
    }
});

const validateEmail = (email) => {
    return email.match(
      /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
  };

mailInput.addEventListener("change", function (e) {
    if (/\S/.test(document.getElementById('mail').value)) {
        document.getElementById('mail').classList.remove("required-input");
        document.getElementById('span_mail').style.visibility = 'hidden';
        email = document.getElementById('mail').value
        console.log(validateEmail(email))
        if(validateEmail(email)){
            console.log('1',validateEmail(email))
            document.getElementById('span_mobile2').style.visibility = 'hidden';
        } else{
            console.log('2',validateEmail(email))
            document.getElementById('span_mobile2').style.visibility = 'visible';
            document.getElementById('mail').classList.add("required-input");
        }
    } else {
        document.getElementById('mail').classList.add("required-input");
        document.getElementById('span_mail').style.visibility = 'visible';
        document.getElementById('span_mobile2').style.visibility = 'hidden';
    }
});

mobileInput.addEventListener("change", function (e) {
    if (/\S/.test(document.getElementById('mobile').value)) {
        document.getElementById('mobile').classList.remove("required-input");
        document.getElementById('span_mobile').style.visibility = 'hidden';
        document.getElementById('span_mobile3').style.visibility = 'hidden';
        if(((document.getElementById('mobile').value).length == 8) && (!isNaN(document.getElementById('mobile').value))){
            // console.log(' 8 char ', (document.getElementById('mobile').value).length)
            document.getElementById('span_mobile1').style.visibility = 'hidden';
        } else{
            // console.log(' 8 char ', (document.getElementById('mobile').value).length)
            document.getElementById('span_mobile1').style.visibility = 'visible';
            document.getElementById('mobile').classList.add("required-input");
        }

    } else {
        document.getElementById('mobile').classList.add("required-input");
        document.getElementById('span_mobile3').style.visibility = 'hidden';
        document.getElementById('span_mobile1').style.visibility = 'hidden';
        document.getElementById('span_mobile').style.visibility = 'visible';
    }
});
villeInput.addEventListener("change", function (e) {
    if (/\S/.test(document.getElementById('ville').value)) {
        document.getElementById('ville').classList.remove("required-input");
        document.getElementById('span_ville').style.visibility = 'hidden';
    } else {
        document.getElementById('ville').classList.add("required-input");
        document.getElementById('span_ville').style.visibility = 'visible';
    }
});




