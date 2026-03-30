const fromInfo = {};

// button Suivant & precedent
const formBtn1 = document.querySelector("#btn-1");

const formBtnPrev2 = document.querySelector("#btn-2-prev");
const formBtnNext2 = document.querySelector("#btn-2-next");

const formBtnPrev3 = document.querySelector("#btn-3-prev");
const formBtnNext3 = document.querySelector("#btn-3-next");

const formBtnPrev4 = document.querySelector("#btn-4-prev");
const formBtnNext4 = document.querySelector("#btn-4-next");

const formBtnPrev5 = document.querySelector("#btn-5-prev");
const formBtnNext5 = document.querySelector("#btn-5-next");

const formBtnPrev6 = document.querySelector("#btn-6-prev");
const formBtnNext6 = document.querySelector("#btn-6-next");

const formBtnPrev7 = document.querySelector("#btn-7-prev");
const formBtnNext7 = document.querySelector("#btn-7-final");

const formBtnPrev8 = document.querySelector("#btn-8-prev");

// Field selection genre

const formgenreh = document.querySelector("#checkgenre_homme");
const formgenref = document.querySelector("#checkgenre_femme");

// Field selection objectif

const formobje = document.querySelector("#check_obj_e");
const formobjs = document.querySelector("#check_obj_s");
const formobjm = document.querySelector("#check_obj_m");
const formobjb = document.querySelector("#check_obj_b");

// Field selection frequence 1

const formfrq0 = document.querySelector("#check_frequence_0");
const formfrq13 = document.querySelector("#check_frequence_13");
const formfrq45 = document.querySelector("#check_frequence_45");
const formfrq67 = document.querySelector("#check_frequence_67");

const formkcal = document.querySelector("#inkcal");

// Field selection frequence 2
const formfrq1 = document.querySelector("#check_frequence_1");
const formfrq2 = document.querySelector("#check_frequence_2");

const formsx = document.querySelector("#base_container").innerHTML;

const formpoid = document.querySelector("#poid");
const formtaille = document.querySelector("#taille");
const formdate = document.querySelector("#date");

// const nomInput = document.getElementById("nom");
// const prenomInput = document.getElementById("prenom");
// const mailInput = document.getElementById("mail");
// const mobileInput = document.getElementById("mobile");
// const villeInput = document.getElementById("ville");

// Buttons functions :

// Button listener of form 1
formBtn1.addEventListener("click", function (e) {
  gotoNextForm(formBtn1, formBtnNext2, 1, 2);
  e.preventDefault();
});

// Next button listener of form 2
formBtnNext2.addEventListener("click", function (e) {
  gotoNextForm(formBtnNext2, formBtnNext3, 2, 3);
  e.preventDefault();
});

// Previous button listener of form 2
formBtnPrev2.addEventListener("click", function (e) {
  gotoNextForm(formBtnNext2, formBtn1, 2, 1);
  e.preventDefault();
  step = 2;
  document.querySelector(`.step--${step}`).classList.remove("step-active");
});

// Previous button listener of form 3
formBtnNext3.addEventListener("click", function (e) {
  gotoNextForm(formBtnNext3, formBtnNext4, 3, 4);
  this.scrollTop = 0;
  e.preventDefault();
});

// Previous button listener of form 3
formBtnPrev3.addEventListener("click", function (e) {
  gotoNextForm(formBtnNext3, formBtnNext2, 3, 2);
  e.preventDefault();
  step = 3;
  document.querySelector(`.step--${step}`).classList.remove("step-active");
});

// Previous button listener of form 4
formBtnNext4.addEventListener("click", function (e) {
  calculekcal();
  gotoNextForm(formBtnNext4, formBtnNext5, 4, 5);
  e.preventDefault();
});
// Previous button listener of form 4
formBtnPrev4.addEventListener("click", function (e) {
  gotoNextForm(formBtnNext4, formBtnNext3, 4, 3);
  e.preventDefault();
  step = 4;
  document.querySelector(`.step--${step}`).classList.remove("step-active");
});

// Previous button listener of form 5
formBtnNext5.addEventListener("click", function (e) {
  if (document.getElementById("kcal").value) {
    fromInfo.kcal = document.getElementById("kcal").value;
  }
  gotoNextForm(formBtnNext5, formBtnNext6, 5, 6);
  e.preventDefault();
});
// Previous button listener of form 5
formBtnPrev5.addEventListener("click", function (e) {
  gotoNextForm(formBtnNext5, formBtnNext4, 5, 4);
  e.preventDefault();
  step = 5;
  document.querySelector(`.step--${step}`).classList.remove("step-active");
});

// Previous button listener of form 6
formBtnNext6.addEventListener("click", function (e) {
  gotoNextForm(formBtnNext6, formBtnNext7, 6, 7);


    const keyMapping_2 = {
        "genre": "gender",
        "objectif": "goal",
        "poids": "weight",
        "date_naissance_client": "birth_date",
        "taille": "height",
        "frequence_sportif": "activity_level",
        "nb_calories": "calories_needed",
        "proteine": "protein",
        "glucide": "carbohydrates",
        "lipide": "fats",
        "age": "age",
        "kcal": "calories_intake",
        "type_consultation": "consultation_type",
    };



    const translatedInfo = Object.fromEntries(
        Object.entries(fromInfo).map(([key, value]) => [keyMapping_2[key] || key, value])
    );

    localStorage.setItem("final_data", JSON.stringify(translatedInfo));
    console.log("After", translatedInfo);


  //document.getElementById('kcal-jour').textContent = 'Vous avez besoin de ' + fromInfo.kcal + ' kcal/jour';
  //document.getElementById('kcal-consommer').textContent = 'Il vous restera à consommer chez vous ' + fromInfo.kcal + ' kcal/jour';
  e.preventDefault();
});

// Previous button listener of form 6
formBtnPrev6.addEventListener("click", function (e) {
  gotoNextForm(formBtnNext6, formBtnNext5, 6, 5);
  e.preventDefault();
  step = 6;
  document.querySelector(`.step--${step}`).classList.remove("step-active");
});

// formBtnPrev7.addEventListener("click", function (e) {
//   gotoNextForm(formBtnNext7, formBtnNext6, 7, 6);
//   e.preventDefault();
//   step = 7;
//   document.querySelector(`.step--${step}`).classList.remove("step-active");
// });

const gotoNextForm = (prev, next, stepPrev, stepNext) => {
  // Get form through the button
// if(next== "final")
// {
//     // action to the final form
//     alert('final');
// }

  const prevForm = prev.parentElement;
  const nextForm = next.parentElement;
  const nextStep = document.querySelector(`.step--${stepNext}`);
  const prevStep = document.querySelector(`.step--${stepPrev}`);
  // Add active/inactive classes to both previous and next form
  nextForm.classList.add("form-active");
  nextForm.classList.add("form-active-animate");
  prevForm.classList.add("form-inactive");
  // Change the active step element
  // prevStep.classList.remove("step-active")
  nextStep.classList.add("step-active");
  // Remove active/inactive classes to both previous an next form
  setTimeout(() => {
    prevForm.classList.remove("form-active");
    prevForm.classList.remove("form-inactive");
    nextForm.classList.remove("form-active-animate");
  }, 99999999999999999999999999999999999999999999999 * 9);
};

// selection fields functions :

// Button genre homme form 1
formgenreh.addEventListener("click", function (e) {
  document
    .querySelector(`#checkgenre_homme`)
    .classList.add("form_check_active");
  document
    .querySelector(`#checkgenre_femme`)
    .classList.remove("form_check_active");

  document.getElementById("genre").value = "Male";
  console.log("form1", document.getElementById("genre").value);
  fromInfo.genre = "Male";

  document.querySelector("#f").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#h").classList.add("cardTextSelected___19-wV");
  document.getElementById("icon_h").src = "online-assessment/static/hh.svg";
  document.getElementById("icon_f").src = "online-assessment/static/f.svg";
  const button = (document.querySelector("#btn-1").disabled = false);
});
// Button genre femme form 1
formgenref.addEventListener("click", function (e) {
  document
    .querySelector(`#checkgenre_homme`)
    .classList.remove("form_check_active");
  document
    .querySelector(`#checkgenre_femme`)
    .classList.add("form_check_active");

  document.getElementById("genre").value = "Female";
  console.log("form1", document.getElementById("genre").value);
  fromInfo.genre = "Female";

  document.querySelector("#h").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#f").classList.add("cardTextSelected___19-wV");
  document.getElementById("icon_f").src = "online-assessment/static/ff.svg";
  document.getElementById("icon_h").src = "online-assessment/static/h.svg";

  const button = (document.querySelector("#btn-1").disabled = false);
});

// Button objectifs form 2
formobje.addEventListener("click", function (e) {
  document.querySelector(`#check_obj_e`).classList.add("form_check_active");
  document.querySelector(`#check_obj_s`).classList.remove("form_check_active");
  document.querySelector(`#check_obj_m`).classList.remove("form_check_active");
  document.querySelector(`#check_obj_b`).classList.remove("form_check_active");
  document.getElementById("objectif").value = "Perte de poids";
  fromInfo.objectif = "Weight loss";
  document.querySelector("#s").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#m").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#b").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#e").classList.add("cardTextSelected___19-wV");
  document.getElementById("icon_e").src = "online-assessment/static/ee.svg";
  document.getElementById("icon_s").src = "online-assessment/static/s.svg";
  document.getElementById("icon_m").src = "online-assessment/static/m.svg";
  document.getElementById("icon_b").src = "online-assessment/well-being-2.png";
  const button = (document.querySelector("#btn-2-next").disabled = false);
});
// Button objectifs form 2
formobjs.addEventListener("click", function (e) {
  document.querySelector(`#check_obj_s`).classList.add("form_check_active");
  document.querySelector(`#check_obj_e`).classList.remove("form_check_active");
  document.querySelector(`#check_obj_m`).classList.remove("form_check_active");
  document.querySelector(`#check_obj_b`).classList.remove("form_check_active");
  document.getElementById("objectif").value = "Sèche musculaire";
  fromInfo.objectif = "Muscle dryness";
  document.querySelector("#e").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#m").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#b").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#s").classList.add("cardTextSelected___19-wV");
  document.getElementById("icon_e").src = "online-assessment/static/e.svg";
  document.getElementById("icon_s").src = "online-assessment/static/ss.svg";
  document.getElementById("icon_m").src = "online-assessment/static/m.svg";
  document.getElementById("icon_b").src = "online-assessment/Well-Being-2.png";
  const button = (document.querySelector("#btn-2-next").disabled = false);
});
// Button objectifs form 2
formobjm.addEventListener("click", function (e) {
  document.querySelector(`#check_obj_m`).classList.add("form_check_active");
  document.querySelector(`#check_obj_e`).classList.remove("form_check_active");
  document.querySelector(`#check_obj_s`).classList.remove("form_check_active");
  document.querySelector(`#check_obj_b`).classList.remove("form_check_active");
  document.getElementById("objectif").value = "Prise de masse";
  fromInfo.objectif = "Mass gain";

  document.querySelector("#s").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#e").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#b").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#m").classList.add("cardTextSelected___19-wV");
  document.getElementById("icon_e").src = "online-assessment/static/e.svg";
  document.getElementById("icon_s").src = "online-assessment/static/s.svg";
  document.getElementById("icon_m").src = "online-assessment/static/mm.svg";
  document.getElementById("icon_b").src = "online-assessment/well-being-2.png";
  const button = (document.querySelector("#btn-2-next").disabled = false);
});
// Button objectifs form 2
formobjb.addEventListener("click", function (e) {
  document.querySelector(`#check_obj_b`).classList.add("form_check_active");
  document.querySelector(`#check_obj_e`).classList.remove("form_check_active");
  document.querySelector(`#check_obj_m`).classList.remove("form_check_active");
  document.querySelector(`#check_obj_s`).classList.remove("form_check_active");
  document.getElementById("objectif").value = "Bien être";
  fromInfo.objectif = "Well-being";

  document.querySelector("#e").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#m").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#s").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#b").classList.add("cardTextSelected___19-wV");
  document.getElementById("icon_e").src = "online-assessment/static/e.svg";
  document.getElementById("icon_s").src = "online-assessment/static/s.svg";
  document.getElementById("icon_m").src = "online-assessment/static/m.svg";
  document.getElementById("icon_b").src = "online-assessment/well-being-2.png";
  const button = (document.querySelector("#btn-2-next").disabled = false);
});

function convertdate(date) {
  var date = date;
  var [year, month, day] = date.split("-");
  var newDate = `${day}-${month}-${year}`;

  return newDate;
}

function dateIsValid(date) {
  date = new Date(convertdate(date));
  return True;
}

// button poid&taille form 3
formpoid.addEventListener("change", function (e) {
  var poid = document.getElementById("poid").value;
  var date = document.getElementById("date").value;
  var taille = document.getElementById("taille").value;
  date = new Date(convertdate(date));
  fromInfo.poids = poid;
  fromInfo.date_naissance_client = date;
  fromInfo.taille = taille;

  if (poid <= 500 && poid >= 30) {
    document.querySelector("#check_p").classList.add("form_check_active");
    document.getElementById("icon_p").src = "online-assessment/static/pp.svg";
  } else {
    document.querySelector("#check_p").classList.remove("form_check_active");
    document.getElementById("icon_p").src = "online-assessment/static/p.svg";
  }

  if (poid <= 500 && poid >= 30 && taille >= 30 && taille <= 300 && date) {
    const button = (document.querySelector("#btn-3-next").disabled = false);
  } else {
    const button = (document.querySelector("#btn-3-next").disabled = true);
  }
});
// button poid&taille form 3
formtaille.addEventListener("change", function (e) {
  var poid = document.getElementById("poid").value;
  var date = document.getElementById("date").value;
  var taille = document.getElementById("taille").value;
  fromInfo.poids = poid;
  fromInfo.date_naissance_client = date;
  fromInfo.taille = taille;

  if (taille >= 30 && taille <= 300) {
    document.querySelector("#check_t").classList.add("form_check_active");
    document.getElementById("icon_t").src = "online-assessment/static/tt.svg";
  } else {
    document.querySelector("#check_t").classList.remove("form_check_active");
    document.getElementById("icon_t").src = "online-assessment/static/t.svg";
  }

  if (poid <= 500 && poid >= 30 && taille >= 30 && taille <= 300 && date) {
    const button = (document.querySelector("#btn-3-next").disabled = false);
  } else {
    const button = (document.querySelector("#btn-3-next").disabled = true);
  }
});

// button poid&taille form 3
formdate.addEventListener("change", function (e) {
  var date = document.getElementById("date").value;
  var poid = document.getElementById("poid").value;
  var taille = document.getElementById("taille").value;
  fromInfo.poids = poid;
  fromInfo.date_naissance_client = date;
  fromInfo.taille = taille;

  if (date) {
    document.querySelector("#check_d").classList.add("form_check_active");
    document.getElementById("icon_d").src = "online-assessment/static/dd.svg";
  } else {
    document.querySelector("#check_d").classList.remove("form_check_active");
    document.getElementById("icon_d").src = "online-assessment/static/d.svg";
  }

  if (poid <= 500 && poid >= 30 && taille >= 30 && taille <= 300 && date) {
    const button = (document.querySelector("#btn-3-next").disabled = false);
  } else {
    const button = (document.querySelector("#btn-3-next").disabled = true);
  }
});

// Button frequences form 4
formfrq0.addEventListener("click", function (e) {
  document
    .querySelector(`#check_frequence_0`)
    .classList.add("form_check_active");
  document
    .querySelector(`#check_frequence_13`)
    .classList.remove("form_check_active");
  document
    .querySelector(`#check_frequence_45`)
    .classList.remove("form_check_active");
  document
    .querySelector(`#check_frequence_67`)
    .classList.remove("form_check_active");
  document.getElementById("frequence1").value = "Sédentaire";
  fromInfo.frequence_sportif = "Sedentary";

  document.querySelector("#f0").classList.add("cardTextSelected___19-wV");
  document.querySelector("#f13").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#f45").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#f67").classList.remove("cardTextSelected___19-wV");
  document.getElementById("icon_f0").src = "online-assessment/static/ff0.svg";
  document.getElementById("icon_f13").src = "online-assessment/static/f13.svg";
  document.getElementById("icon_f45").src = "online-assessment/static/f45.svg";
  document.getElementById("icon_f67").src = "online-assessment/static/f67.svg";
  const button = (document.querySelector("#btn-4-next").disabled = false);
});
// Button frequences form 4
formfrq13.addEventListener("click", function (e) {
  document
    .querySelector(`#check_frequence_13`)
    .classList.add("form_check_active");
  document
    .querySelector(`#check_frequence_0`)
    .classList.remove("form_check_active");
  document
    .querySelector(`#check_frequence_45`)
    .classList.remove("form_check_active");
  document
    .querySelector(`#check_frequence_67`)
    .classList.remove("form_check_active");
  document.getElementById("frequence1").value = "Modérément actif";
  fromInfo.frequence_sportif = "Moderately active";

  document.querySelector("#f0").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#f13").classList.add("cardTextSelected___19-wV");
  document.querySelector("#f45").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#f67").classList.remove("cardTextSelected___19-wV");
  document.getElementById("icon_f0").src = "online-assessment/static/f0.svg";
  document.getElementById("icon_f13").src = "online-assessment/static/ff13.svg";
  document.getElementById("icon_f45").src = "online-assessment/static/f45.svg";
  document.getElementById("icon_f67").src = "online-assessment/static/f67.svg";
  const button = (document.querySelector("#btn-4-next").disabled = false);
});
// Button frequences form 4
formfrq45.addEventListener("click", function (e) {
  document
    .querySelector(`#check_frequence_45`)
    .classList.add("form_check_active");
  document
    .querySelector(`#check_frequence_0`)
    .classList.remove("form_check_active");
  document
    .querySelector(`#check_frequence_13`)
    .classList.remove("form_check_active");
  document
    .querySelector(`#check_frequence_67`)
    .classList.remove("form_check_active");
  document.getElementById("frequence1").value = "Actif";
  fromInfo.frequence_sportif = "active";

  document.querySelector("#f0").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#f13").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#f45").classList.add("cardTextSelected___19-wV");
  document.querySelector("#f67").classList.remove("cardTextSelected___19-wV");
  document.getElementById("icon_f0").src = "online-assessment/static/f0.svg";
  document.getElementById("icon_f13").src = "online-assessment/static/f13.svg";
  document.getElementById("icon_f45").src = "online-assessment/static/ff45.svg";
  document.getElementById("icon_f67").src = "online-assessment/static/f67.svg";
  const button = (document.querySelector("#btn-4-next").disabled = false);
});
// Button frequences form 4
formfrq67.addEventListener("click", function (e) {
  document
    .querySelector(`#check_frequence_67`)
    .classList.add("form_check_active");
  document
    .querySelector(`#check_frequence_0`)
    .classList.remove("form_check_active");
  document
    .querySelector(`#check_frequence_13`)
    .classList.remove("form_check_active");
  document
    .querySelector(`#check_frequence_45`)
    .classList.remove("form_check_active");
  document.getElementById("frequence1").value = "Très actif";
  fromInfo.frequence_sportif = "Very active";

  document.querySelector("#f0").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#f13").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#f45").classList.remove("cardTextSelected___19-wV");
  document.querySelector("#f67").classList.add("cardTextSelected___19-wV");
  document.getElementById("icon_f0").src = "online-assessment/static/f0.svg";
  document.getElementById("icon_f13").src = "online-assessment/static/f13.svg";
  document.getElementById("icon_f45").src = "online-assessment/static/f45.svg";
  document.getElementById("icon_f67").src = "online-assessment/static/ff67.svg";
  const button = (document.querySelector("#btn-4-next").disabled = false);
});
function calculeage(date) {
  var today = new Date().getFullYear();
  var date = date;
  var [year, month, day] = date.split("-");
  var newDate = `${day}-${month}-${year}`;
  var year = `${year}`;
  var age = today - year;

  return age;
}

// // Button Form5
function calculekcal() {
  var genre = document.getElementById("genre").value;
  var date = document.getElementById("date").value;
  var age = calculeage(date);
  var poid = document.getElementById("poid").value;
  var taille = document.getElementById("taille").value;
  var objectif = document.getElementById("objectif").value;
  var freq1 = document.getElementById("frequence1").value;
  console.log("taille", taille);
  console.log("poid", poid);
  console.log("age", age);
  //get objectif value Metabloisme F&H
  if (objectif == "Perte de poids") {
    var val_obj = 0.75;
  }

  if (objectif == "Sèche musculaire") {
    var val_obj = 0.85;
  }
  if (objectif == "Prise de masse") {
    var val_obj = 1.1;
  }
  if (objectif == "Bien être") {
    var val_obj = 1;
  }


  console.log("objectif", val_obj);
  //Calccule Metabloisme F&H
  if (genre == "Male") {
    var Metabloisme = 10 * poid + 6.25 * taille - 5 * age + 5;
  }
  if (genre == "Female") {
    var Metabloisme = 10 * poid + 6.25 * taille - 5 * age - 161;
  }
  console.log("Metabloisme", Metabloisme);
  if (objectif == "Perte de poids" || objectif == "Sèche musculaire") {
    if (freq1 == "Sédentaire") {
      var val_f1 = 1.2;
      if (genre == "Male") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
      if (genre == "Female") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
    }
    if (freq1 == "Modérément actif") {
      var val_f1 = 1.375;
      if (genre == "Male") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
      if (genre == "Female") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
    }
    if (freq1 == "Actif") {
      var val_f1 = 1.4;
      if (genre == "Male") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
      if (genre == "Female") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
    }
    if (freq1 == "Très actif") {
      var val_f1 = 1.7;
      if (genre == "Male") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
      if (genre == "Female") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
    }
    console.log("activity", val_f1);
    console.log("Maintien", Maintien);
    console.log("Intake", Intake);
    console.log("prot", tot1);
    console.log("gluc", tot2);
    console.log("lipi", tot3);
  }

  if (objectif == "Prise de masse" || objectif == "Bien être") {
    if (freq1 == "Sédentaire") {
      var val_f1 = 1.2;
      if (genre == "Male") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
      if (genre == "Female") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
    }
    if (freq1 == "Modérément actif") {
      var val_f1 = 1.4;
      if (genre == "Male") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
      if (genre == "Female") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
    }
    if (freq1 == "Actif") {
      var val_f1 = 1.55;
      if (genre == "Male") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
      if (genre == "Female") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
    }
    if (freq1 == "Très actif") {
      var val_f1 = 1.725;
      if (genre == "Male") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
      if (genre == "Female") {
        var Maintien = Metabloisme * val_f1;
        var Intake = Maintien * val_obj;
        var tot1 = poid * 2;
        var tot3 = poid;
        var tot2 = Math.round((Intake - tot1 * 4 - tot3 * 9) / 4);
      }
    }
  }
  // POst Values

  fromInfo.nb_calories = Math.round(Intake / 50) * 50;
  fromInfo.proteine = Math.round(tot1);
  fromInfo.glucide = Math.round(tot2);
  fromInfo.lipide = Math.round(tot3);
  fromInfo.age = age;

  // document.getElementById('total').value = Math.round(Intake);
  // document.getElementById('total_p').value = Math.round(tot1);
  // document.getElementById('total_g').value = tot2;
  // document.getElementById('total_l').value = Math.round(tot3);

  document.getElementById("span_total").innerHTML =
    Math.round(Intake / 50) * 50;
  document.getElementById("span_total_p").innerHTML = Math.round(tot1);
  document.getElementById("span_total_g").innerHTML = Math.round(tot2);
  document.getElementById("span_total_l").innerHTML = Math.round(tot3);
}

function calculekcal2() {
  var poid = document.getElementById("poid").value;
  var kcal = document.getElementById("kcal").value;

  // document.getElementById('total_p').value = Math.round(poid * 2);
  // document.getElementById('total_l').value = Math.round(poid);
  // document.getElementById('total_g').value = Math.round((kcal - (proteines * 4) - (lipides * 9))/4);
  fromInfo.lipide = Math.round(poid);
  fromInfo.proteine = Math.round(poid * 2);
  fromInfo.glucide = Math.round(
    (kcal - fromInfo.proteine * 4 - fromInfo.lipide * 9) / 4
  );

  document.getElementById("span_total_p").innerHTML = fromInfo.proteine;
  document.getElementById("span_total_g").innerHTML = fromInfo.glucide;
  document.getElementById("span_total_l").innerHTML = fromInfo.lipide;
}

// Button frequences form 5
formkcal.addEventListener("change", function (e) {
  var kcal = document.getElementById("kcal").value;
  if (kcal > 0) {
    document.querySelector("#inkcal").classList.add("form_check_active");
    calculekcal2();
  } else {
    document.querySelector("#inkcal").classList.remove("form_check_active");
    calculekcal();
  }
  fromInfo.kcal = kcal;
});

// Button frequences form 6
formfrq1.addEventListener("click", function (e) {
  document
    .querySelector(`#check_frequence_1`)
    .classList.add("form_check_active");
  document
    .querySelector(`#check_frequence_2`)
    .classList.remove("form_check_active");
  document.getElementById("frequence2").value = "sur place";
  fromInfo.type_consultation = "in_person";
  const button = (document.querySelector("#btn-6-next").disabled = false);
  console.log(fromInfo.type_consultation);
});
// Button frequences form 6
formfrq2.addEventListener("click", function (e) {
  document
    .querySelector(`#check_frequence_2`)
    .classList.add("form_check_active");
  document
    .querySelector(`#check_frequence_1`)
    .classList.remove("form_check_active");
  document.getElementById("frequence2").value = "par tel";
  fromInfo.type_consultation = "phone";
  const button = (document.querySelector("#btn-6-next").disabled = false);
  console.log(fromInfo.type_consultation);
});

function Affichage_Message(rec) {
  console.log("function fenetre message !");

  document
    .querySelector(`#container__stepper`)
    .classList.remove("message_formhidden_true");
  document
    .querySelector(`#container_last`)
    .classList.remove("message_formhidden_true");

  document
    .querySelector(`#container__stepper`)
    .classList.add("message_formhidden_true");
  document
    .querySelector(`#container_last`)
    .classList.add("message_formhidden_true");

  document.getElementById("base_container").innerHTML =
    '<div class="container_msg" style="margin-top: 63px;"id="container_msg_id"><div class="container___1fUfH"><div class="infoForm"><div class="form_check_body_e"><span style="text-align: center;"><h1 class="text_msg" id="msg"></h1></span><div id="icon_iddiv" style="text-align: center;"></div></div></div></div></div><div id="div_button"></div>';
  document.getElementById("msg").innerHTML = rec.msg;

  if (rec.id == false) {
    document.getElementById("div_button").innerHTML =
      '<a href="/home/shazler/Bureau/yass/eat&fit/App/index.html"><button class="form__btn_ret button___1_PFv" id="btn-8-prev">Précédent</button></a>';
    document.getElementById("icon_iddiv").innerHTML =
      '<img src="static/non.png" style="width: 20%;margin-top: 1%;">';
  } else {
    document.getElementById("icon_iddiv").innerHTML =
      '<img src="static/yes.png" style="width: 70px;margin-top: 1%;">';
  }
}

// formBtnNext7.addEventListener("click", function (e) {
//   var nom = "nom"
//   var prenom = "prenom";
//   var mail = "mail"
//   var mobile = "mobile"
//   var ville = "ville"

//   fromInfo.nom = nom;
//   fromInfo.prenom = prenom;
//   fromInfo.email = mail;
//   fromInfo.phone = mobile;
//   fromInfo.ville = ville;
//   if (!nom) {
//     document.getElementById("nom").classList.add("required-input");
//     document.getElementById("span_nom").style.visibility = "visible";
//   }
//   if (!prenom) {
//     document.getElementById("prenom").classList.add("required-input");
//     document.getElementById("span_prenom").style.visibility = "visible";
//   }
//   if (!mail) {
//     document.getElementById("mail").classList.add("required-input");
//     document.getElementById("span_mail").style.visibility = "visible";
//   }
//   if (!mobile) {
//     document.getElementById("mobile").classList.add("required-input");
//     document.getElementById("span_mobile").style.visibility = "visible";
//   }
//   if (!ville) {
//     document.getElementById("ville").classList.add("required-input");
//     document.getElementById("span_ville").style.visibility = "visible";
//   }
//   if (nom && prenom && mail && mobile && ville) {
    // console.log("fromInfo : ", fromInfo);
    // if (true) {

        // Define the mapping
        // const keyMapping_2 = {
        //     "genre": "gender",
        //     "objectif": "goal",
        //     "poids": "weight",
        //     "date_naissance_client": "birth_date",
        //     "taille": "height",
        //     "frequence_sportif": "activity_level",
        //     "nb_calories": "calories_needed",
        //     "proteine": "protein",
        //     "glucide": "carbohydrates",
        //     "lipide": "fats",
        //     "age": "age",
        //     // "email": "email",
        //     "kcal": "calories_intake",
        //     // "nom": "name",
        //     // "phone": "phone",
        //     // "prenom": "first_name",
        //     "type_consultation": "consultation_type",
        //     // "ville": "city"
        // };



        // const translatedInfo = Object.fromEntries(
        //     Object.entries(fromInfo).map(([key, value]) => [keyMapping_2[key] || key, value])
        // );

        // localStorage.setItem("final_data", JSON.stringify(translatedInfo));
        // console.log("After", translatedInfo);

    // https://hello.eatfit.tn/api/wizard/
    //http://192.168.1.88:8012/api/wizard/
    // $.ajax({
    //   type: "POST",
    //   url: "https://hello.eatfit.tn/api/wizard/",
    //   data: fromInfo,
    //   success: function (msg) {
    //     console.log('--------------SUCCESS---------------');

    //     console.log(fromInfo);
    //     console.log('---------------SUCCESS-----------------');

    //     // response = JSON.stringify(msg);
    //     rec = JSON.parse(msg);
    //     console.log("msg", msg);
    //     console.log("rec", rec);


    //     if (rec[0]["id"] != false) {
    //       Affichage_Message(rec[0]);
    //       console.log("1 msg :", rec[0]["msg"]);
    //     } else {
    //       console.log("2 msg :", rec);
    //       console.log(" msg num :", rec[0]["msg"]);
    //       document.getElementById("span_mobile3").style.visibility = "visible";
    //     }
    //   },error: function() {
    //     console.log('---------------ERROR------------------');

    //     console.log(fromInfo);
    //     console.log('---------------ERROR-----------------');
    // }
    // });
//   }
// });

if (document.getElementById("nom")) {
    document.getElementById("nom").addEventListener("change", function (e) {
        if (/\S/.test(this.value)) {
            this.classList.remove("required-input");
            document.getElementById("span_nom").style.visibility = "hidden";
        } else {
            this.classList.add("required-input");
            document.getElementById("span_nom").style.visibility = "visible";
        }
    });
}

if (document.getElementById("prenom")) {
    document.getElementById("prenom").addEventListener("change", function (e) {
        if (/\S/.test(this.value)) {
            this.classList.remove("required-input");
            document.getElementById("span_prenom").style.visibility = "hidden";
        } else {
            this.classList.add("required-input");
            document.getElementById("span_prenom").style.visibility = "visible";
        }
    });
}

if (document.getElementById("mail")) {
    document.getElementById("mail").addEventListener("change", function (e) {
        if (/\S/.test(this.value) && validateEmail(this.value)) {
            this.classList.remove("required-input");
            document.getElementById("span_mail").style.visibility = "hidden";
        } else {
            this.classList.add("required-input");
            document.getElementById("span_mail").style.visibility = "visible";
        }
    });
}

if (document.getElementById("mobile")) {
    document.getElementById("mobile").addEventListener("change", function (e) {
        if (/\S/.test(this.value) && this.value.length === 8 && !isNaN(this.value)) {
            this.classList.remove("required-input");
            document.getElementById("span_mobile1").style.visibility = "hidden";
        } else {
            this.classList.add("required-input");
            document.getElementById("span_mobile1").style.visibility = "visible";
        }
    });
}

if (document.getElementById("ville")) {
    document.getElementById("ville").addEventListener("change", function (e) {
        if (/\S/.test(this.value)) {
            this.classList.remove("required-input");
            document.getElementById("span_ville").style.visibility = "hidden";
        } else {
            this.classList.add("required-input");
            document.getElementById("span_ville").style.visibility = "visible";
        }
    });
}



// Function to save form data to localStorage
function saveToLocalStorage(key, value) {
    let storedData = loadFromLocalStorage();
    storedData[key] = value;
    localStorage.setItem("formData", JSON.stringify(storedData));
}

// Function to load form data from localStorage
function loadFromLocalStorage() {
    return JSON.parse(localStorage.getItem("formData")) || {};
}

// Restore form data on page load
document.addEventListener("DOMContentLoaded", function () {
    let formData = loadFromLocalStorage();

    Object.keys(formData).forEach((key) => {
        let field = document.querySelector(`#${key}`);
        if (field) {
            if (field.type === "checkbox" || field.type === "radio") {
                field.checked = formData[key];
            } else {
                field.value = formData[key];
            }
        }
    });

    // Add event listeners for all fields
    document.querySelectorAll("input, select").forEach((field) => {
        field.addEventListener("input", function () {
            saveToLocalStorage(field.id, field.value);
        });
    });

    document.querySelectorAll("input[type='checkbox'], input[type='radio']").forEach((field) => {
        field.addEventListener("change", function () {
            saveToLocalStorage(field.id, field.checked);
        });
    });
});

// Gender Selection
document.querySelector("#checkgenre_homme").addEventListener("click", function () {
    saveToLocalStorage("gender", "Male");
});
document.querySelector("#checkgenre_femme").addEventListener("click", function () {
    saveToLocalStorage("gender", "Female");
});

// Objective Selection
document.querySelectorAll("[id^=check_obj_]").forEach((element) => {
    element.addEventListener("click", function () {
        let selectedObjective = this.getAttribute("id").replace("check_obj_", "");
        saveToLocalStorage("objectif", selectedObjective);
    });
});

// // Frequency Selection
// document.querySelectorAll("[id^=check_frequence_]").forEach((element) => {
//     element.addEventListener("click", function () {
//         let selectedFrequency = this.getAttribute("id").replace("check_frequence_", "");
//         saveToLocalStorage("frequence", selectedFrequency);
//     });
// });


document.querySelectorAll("#check_frequence_0, #check_frequence_13, #check_frequence_45, #check_frequence_67").forEach((element) => {
    element.addEventListener("click", function () {
        let selectedFrequency = this.getAttribute("id").replace("check_frequence_", "");
        saveFrequence1(selectedFrequency);
    });
});

document.querySelectorAll("#check_frequence_1, #check_frequence_2").forEach((element) => {
    element.addEventListener("click", function () {
        let selectedFrequency = this.getAttribute("id").replace("check_frequence_", "");
        saveFrequence2(selectedFrequency);
    });
});



// Save frequence1 value
function saveFrequence1(value) {
    saveToLocalStorage("frequence1", value);
}

// Save frequence2 value
function saveFrequence2(value) {
    saveToLocalStorage("frequence2", value);
}

// Load frequence1 value
function loadFrequence1() {
    return loadFromLocalStorage("frequence1");
}

// Load frequence2 value
function loadFrequence2() {
    return loadFromLocalStorage("frequence2");
}


// ----------------------------------------------------

// Form submission handling
// document.querySelector("#finalSubmitButton").addEventListener("click", function (e) {
//     e.preventDefault();

//     let storedData = loadFromLocalStorage();

//     // Check if storedData is empty
//     if (!storedData || Object.keys(storedData).length === 0) {
//         alert("No data to submit. Please fill out the form.");
//         return;
//     }

//     // Mapping French keys to English
//     const translationMap = {
//         "genre": "gender",
//         "objectif": "objective",
//         "poid": "weight",
//         "taille": "height",
//         "date_naissance_client": "birth_date",
//         "frequence": "activity_level",
//         "kcal": "calories",
//         "nom": "first_name",
//         "prenom": "last_name",
//         "mail": "email",
//         "mobile": "phone",
//         "ville": "city"
//     };

//     const objectiveMap = {
//         "e": "Weight Loss",
//         "s": "Dry Muscle",
//         "m": "Mass Gain",
//         "b": "Well-Being"
//     };

//     const frequencyMap = {
//         "0": "Sedentary",
//         "13": "Moderate Active",
//         "45": "Active",
//         "67": "Very Active"
//     };

//     let transformedData = {};

//     // Convert keys and values
//     Object.keys(storedData).forEach((key) => {
//         if (translationMap[key]) {
//             let newKey = translationMap[key];
//             let newValue = storedData[key];

//             if (newKey === "objective" && objectiveMap[newValue]) {
//                 newValue = objectiveMap[newValue];
//             }
//             if (newKey === "activity_level" && frequencyMap[newValue]) {
//                 newValue = frequencyMap[newValue];
//             }

//             transformedData[newKey] = newValue;
//         }
//     });

//     const finalData = { "online_assessment": transformedData };

//     // Disable submit button to prevent multiple clicks
//     const submitButton = document.querySelector("#finalSubmitButton");
//     submitButton.disabled = true;
//     submitButton.textContent = "Submitting...";

//     fetch("your-api-endpoint", {
//         method: "POST",
//         headers: {
//             "Content-Type": "application/json",
//         },
//         body: JSON.stringify(finalData),
//     })
//     .then(response => {
//         if (!response.ok) {
//             throw new Error(`Server error: ${response.status}`);
//         }
//         return response.json();
//     })
//     .then(data => {
//         console.log("Success:", data);

//         // Show success message
//         alert("Form submitted successfully!");

//         // Clear data only after successful submission
//         localStorage.removeItem("formData");

//         // Optionally redirect to another page
//         window.location.href = "/thank-you.html";
//     })
//     .catch(error => {
//         console.error("Error:", error);
//         alert(`Failed to submit form: ${error.message}`);

//         // Re-enable the button in case of failure
//         submitButton.disabled = false;
//         submitButton.textContent = "Submit";
//     });
// });
