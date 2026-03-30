@push('extra-css')

<style>
/*      MEnU*/


/*  //////////////////////////////////////////////*/
/*  END  MEnu_AN_*/
/*  //////////////////////////////////////////////// */
/*         #meal-options-accordion {*/
/*    display: flex;*/
/*}*/

/*#meal-options-accordion .meal-section {*/
/*    width: 50%;

                                                                                                                            rgb(29, 181, 60)
                                                                                                                            */
/*} */


/*  MENU _AN*/
.form-horizontal {
  /*     overflow-y: scroll; */
  padding: 1rem;
}

.meals {
  max-width: 38rem;
  border: 1px solid #d3d3d3;
  border-radius: 10px;
  padding: 10px;
  margin-bottom: 20px;
  position: relative;
}

.meal-active {
  border: 2px solid #a0b63b;
}

/*         .selected-meal { */
/*              */
/*             border: 3px solid #16a34a !important; */
/*             border: 3px solid red !important; */
/*         } */

.selected-meal,
.selected-meal .content {
  position: relative;

  padding: 4px;
  border-radius: 1rem;
  background: linear-gradient(45deg,
      #16a34a,
      #22c55e,
      #4ade80,
      #16a34a);
  background-size: 400% 400%;
  animation: border-animation 3s ease infinite;

  p,
  h4 {
    color: white;
  }
}

@keyframes border-animation {
  0% {
    background-position: 0% 50%;
  }

  50% {
    background-position: 100% 50%;
  }

  100% {
    background-position: 0% 50%;
  }
}

.content {
  color: white !important;
  /*        padding: 2rem; */
  /*        border-radius: 0.8rem; */
}

/*      @media (min-width: 640px) { */
/*        .content { */
/*          padding: 3rem; */
/*        } */
/*      } */

.circle {
  position: absolute;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fff;
  border: 1px solid #d3d3d3;
  height: 25px;
  transform: scaleX(1);
  width: 25px;
  right: -14px;
  top: -15px;
  border-radius: 50%;
  box-sizing: content-box;
}

.circle:before {
  content: "";
  position: absolute;
  width: 20%;
  height: 13px;
  background: #fff;
  bottom: -14.1px;
  left: 41%;
}

.meals.meal-active .circle {
  background: #a0b63b;
}

.meals.meal-active .circle i {
  color: #fff;
}

.meal-data {
  display: flex;
  /* justify-content: space-between; */
  gap: 1rem;
}

.meal-data {
  /*         flex-direction: column; */
}

/* .meal-img { */
/*     width: 25%; */
/*     max-width: 150px; */
/*     height: 150px; */
/*     border-radius: 8px; */
/*     overflow: hidden; */
/* } */
/* .meal-img img { */
/*     width: 100%; */
/*     object-fit: cover; */
/*     height: 100%; */
/*     object-position: center; */
/* } */
/*  .meal-img { */
/*         width: auto; */
/*         max-width: inherit; */
/*     } */


.meal-info {
  width: 75%;
}

.meal-info {
  width: auto;
}

.meal-name-macros {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 10px 10px 15px 0px;
}

.meal-name-macros {
  flex-direction: column;
}

.meal-name {
  font-weight: 500;
  font-size: 18px;
  line-height: 23px;
}

.macros {
  display: flex;
  flex-wrap: nowrap;
}

.cal {
  display: inline-flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  border-radius: 50px;
  background-color: #e9e9e9;
  padding: 2px;
  margin: 0 3px;
}

.cal .num {
  border-radius: 50%;
  background-color: #fff;
  font-weight: 500;
  font-size: 9px;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.cal .title {
  font-size: 9px;
  line-height: 14px;
  color: #717171;
  padding: 2.5px;
}

.about-meal {
  font-weight: 400;
  font-size: 15px;
  line-height: 21px;
  color: #717171;
}

.meal-choice {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.input-filed {
  display: flex;
  max-width: 120px;
  /*border: 1px solid #d3d3d3;*/
  border-radius: 4px;
  justify-content: space-evenly;
}

.input-filed input {
  outline: none;
  border: 0;
  width: 33%;
  text-align: right;
  background: #e9ebef;
}

.input-filed {
  max-width: inherit;
}

.input-filed input {
  text-align: center;
}

.checkbox-wrapper input[type="radio"] {
  display: none !important;
}

.checkbox-wrapper .terms-label {
  cursor: pointer;
  display: flex;
  align-items: center;
}

.checkbox-wrapper .terms-label .label-text {
  margin-left: 10px;
  color: #d3d3d3;
  font-weight: 400;
}

.checkbox-wrapper .checkbox-svg {
  width: 30px;
  height: 30px;
}

.checkbox-wrapper .checkbox-box {
  fill: rgba(207, 205, 205, 0.425);
  stroke: #a0B63B;
  stroke-dasharray: 800;
  stroke-dashoffset: 800;
  transition: stroke-dashoffset 0.6s ease-in;
}

.checkbox-wrapper .checkbox-tick {
  stroke: #a0B63B;
  stroke-dasharray: 172;
  stroke-dashoffset: 172;
  transition: stroke-dashoffset 0.6s ease-in;
}

.checkbox-wrapper input[type="radio"]:checked+.terms-label .label-text {
  color: #a0B63B;
}

.checkbox-wrapper input[type="radio"]:checked+.terms-label .checkbox-box,
.checkbox-wrapper input[type="radio"]:checked+.terms-label .checkbox-tick {
  fill: #fff;
  stroke-dashoffset: 0;
}

.checkbox-wrapper .terms-label {
  cursor: pointer;
  display: flex;
  align-items: center;
}

.checkbox-wrapper .terms-label .label-text {
  margin-left: 10px;
  color: #d3d3d3;
  font-weight: 400;
}

.checkbox-wrapper input[type="radio"]:checked+.terms-label .label-text {
  color: #a0B63B;
}

.checkbox-wrapper input[type="radio"]:checked+.terms-label .checkbox-box,
.checkbox-wrapper input[type="radio"]:checked+.terms-label .checkbox-tick {
  fill: #fff;
  stroke-dashoffset: 0;
}

.add-note-btn {
  background-color: transparent;
  outline: 0;
  border: 0;
  font-size: 15px;
  line-height: 20px;
  color: #a0b63b;
  cursor: pointer;
  margin-right: 15px;
}

/* .submit-menu-btn-div { */
/*   position: absolute; */
/*   bottom: 0; */
/*   width: calc(100% - 70px); */
/*   display: flex; */
/*   justify-content: center; */
/*   padding: 15px; */
/*   background: #fff; */
/* } */

.submit-menu-btn-div {
  padding: 10px;
  width: -webkit-fill-available;
}


.menu-sumbit {
  border: 0;
  background-color: #a0b63b;
  color: #fafafa;
  text-decoration: none;
  border-radius: 8px;
  padding: 10px 40px;
  text-align: center;
  font-weight: 600;
  font-size: 1em;
  line-height: 140%;
  transition: all 0.3s ease-in-out;
}

.menu-sumbit:hover {
  background-color: #8fa335;
}

.menu-sumbit:active {
  background-color: #7e8f2f !important;
}

/* General styling for the accordion items */
/* Style for the close button */
.dialog-container {}


.close-dialog {
  position: absolute;
  top: 0.5rem;
  /* Equivalent to top-2 */
  right: 0.5rem;
  /* Equivalent to right-2 */
  color: #6b7280;
  /* Equivalent to text-gray-500 */
  font-weight: bold;
  /* Equivalent to font-bold */
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.5rem;
  /* Adjust size as needed */
  line-height: 1;
}

/* Style for the dialog image */
/* .dialog-image { */
/*     border-radius: 0.25rem; Equivalent to rounded */

/* } */

/* Style for the dialog title */
/* .dialog-title { */
/*     font-weight: bold;  */
/* Equivalent to font-bold */
/*     font-size: 1.125rem;  */
/* Equivalent to text-lg */
/*     line-height: 1.75rem;  */
/* Equivalent to text-lg */
/*     margin-bottom: 0.5rem;  */
/* Equivalent to mb-2 */
/* } */

/* Style for the dialog description */
.dialog-description {
  color: #4b5563;
  /* Equivalent to text-gray-600 */
}

/*  ////////////////End Dialog Styles _AN/////////////////// */

.meal-image {
  /*   max-width: 100%; */
  /*   border-radius: 8px; */
  /*   margin-bottom: 10px; */
  /* cursor: pointer; */
}


/* Modal dialog styling */
.meal-dialog {
  max-height: 99vh;

  /*   max-width: 50%; */
  /*   border: 2px solid #64626a; */
  border-radius: 5px;
  min-width: 17.5rem;
  padding: 2rem;
  /*   max-width: 400px; */
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  animation: fadeIn 0.3s ease;
}

.dialog-content {
  position: relative;
  padding: 20px;
  background-color: #fff;
  border-radius: 10px;
}

.close-dialog:hover {
  color: #333;
}

/*  END Dialog Styling __AN__*/
/* Animation for dialog */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }

  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* ////////////////////////////////////// */
.meal-section {
  cursor: pointer;

  flex: 1;
  /* padding: 20px; */
  background-color: transparent;
  /*border: 1px 0 solid var(--theme-color); */
  border-radius: 5px;
  margin-top: 20px;
  border: 1px dashed var(--theme-color) !important;
  padding: 20px 30px;
  border-radius: 10px;
  transition: background-color 0.3s ease-in-out;
}

.meal-option {
  display: flex;
  padding: 10px;
  border: 1px dashed var(--theme-color);
  border-radius: 8px;
  margin-bottom: 10px;
  transition: box-shadow 0.3s ease;
}

.meal-option:hover {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.meal-desc h4 {
  padding-top: 20px;
  padding-bottom: 20px;
  margin-bottom: 5px;
}

.meal-option img {
  width: 85px;
  height: 85px !important;
  margin-right: 10px;
}

#tab-1400 {
  display: block !important;
}

.block {
  display: block !important;
}

/* From Uiverse.io by javierBarroso */


/* From Uiverse.io by gharsh11032000 */
/* From Uiverse.io by Mike11jr */
.scene {
 width: 10em;
 justify-content: center;
 align-items: center;
}

.cube {
 color: #ccc;
 cursor: pointer;
 font-family: 'Roboto', sans-serif;
 transition: all 0.85s cubic-bezier(.17,.67,.14,.93);
 transform-style: preserve-3d;
 transform-origin: 100% 50%;
 width: 10em;
 height: 4em;
}

.cube:hover {
 transform: rotateX(-90deg);
}

.side {
 box-sizing: border-box;
 position: absolute;
 display: inline-block;
 height: 4em;
 width: 10em;
 text-align: center;
 text-transform: uppercase;
 padding-top: 1.5em;
 font-weight: bold;
}

.top {
 background: #22c55e;
 color: #fff;

 transform: rotateX(90deg) translate3d(0, 0, 2em);
 box-shadow: inset 0 0 0 5px #fff;
}

.front {
 background: #FFF;
 color: #22c55e;
  border: 2px solid #22c55e;
  border-radius: 4px;
 box-shadow: inset 0 0 0 5px #fff;
 transform: translate3d(0, 0, 2em);
}
.cube .front {
  background: #FFF;
  color: #22c55e;
  box-shadow: inset 0 0 0 5px #fff;
  transform: translate3d(0, 0, 2em);
 transition: background 0.3s cubic-bezier(.17, .67, .14, .93), color 0.3s cubic-bezier(.17, .67, .14, .93);

}

/* لما يكون active نخليه أخضر */
.cube.active .front {
  background: #22c55e;
  color: #FFF;
}
/* From Uiverse.io by Praashoo7 */
/* -- FULL VERSION OF THIS CARD[WITH SOUND] IS AVAILABLE HERE - praashoo7.github.io/NFS-MW-MenuCard -- */

.main {
  display: flex;
  align-items: flex-start;
  justify-content: center;
  flex-direction: column;
  gap: 1em;
  user-select: none;
}
.mainTitle {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}
.mainTitle span {
  color: white;
  font-weight: bold;
  font-family: Montserrat Regular;
  margin-top: -2em;
  margin-bottom: 2em;
  letter-spacing: 0.9em;
  margin-left: 0.8em;
  font-size: 0.85em;
}
.bottomText {
  margin-top: 1em;
  color: white;
  font-weight: bold;
  font-family: Montserrat Regular;
}

.open:checked ~ .overlaystander {
  display: block;
}
.open:checked ~ .menu {
  display: block;
}
.open:checked ~ .corner1 {
  animation: 0.35s openCorner1 linear forwards;
}
.open:checked ~ .corner2 {
  animation: 0.35s openCorner2 linear forwards;
}
.open:checked ~ .corner3 {
  animation: 0.35s openCorner3 linear forwards;
}
.open:checked ~ .corner4 {
  animation: 0.35s openCorner4 linear forwards;
}
.open:checked ~ .menu .middleDiv {
  animation: 0.15s openMiddleMenu linear forwards;
}

.overlaystander {
  position: fixed;
  width: 100%;
  height: 100%;
  background-color: rgb(10, 10, 10, 0.75);
  z-index: 99999;
  display: none;
      top: 0;
    left: 0;
}

.menuWrapper {
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}

.menuWrapper {
  position: fixed;
  width: 403.45px;
  display: none;
  /* height: max-content; */
  z-index: 999999;
}
.menuWrapper .menu,
.menuOpenBtns {
  position: relative;
width: 100%;
  z-index: 3;
}

.menuOpenBtns {
  position: relative;
  width: 17.5em;
  display: block;
  z-index: 1;
  opacity: 1;
}
.selectionBtns {
  display: flex;
  align-items: center;
  justify-content: space-between;
  color: white;
  margin: 1em;
  padding: 1em;
}
.selectionBtns .open {
  text-align: center;
  font-weight: bold;
  font-family: Montserrat Regular;
}
.open {
  position: relative;
}

.corner1,
.corner2,
.corner3,
.corner4 {
  position: absolute;
  width: 1em;
  height: 1em;
  background-color: transparent;
  margin: -1em;
  transform: rotateY(180deg);
  z-index: 4;
}
.corner1 {
  top: 50%;
  left: 50%;
  border-top: 2px solid #22c55e;
  border-left: 2px solid #22c55e;
  transform: rotateZ(180deg);
  animation: 0.35s openCorner1 ease forwards;
}
.corner2 {
  top: 50%;
  right: 50%;
  border-top: 2px solid #22c55e;
  border-right: 2px solid #22c55e;
  transform: rotateZ(180deg);
  animation: 0.35s openCorner2 ease forwards;
}
.corner3 {
  bottom: 50%;
  left: 50%;
  border-bottom: 2px solid #22c55e;
  border-left: 2px solid #22c55e;
  transform: rotateZ(180deg);
  animation: 0.35s openCorner3 ease forwards;
}
.corner4 {
  bottom: 50%;
  right: 50%;
  border-bottom: 2px solid #22c55e;
  border-right: 2px solid #22c55e;
  transform: rotateZ(180deg);
  animation: 0.35s openCorner4 ease forwards;
}


.middleDiv {
  display: flex;
align-items: center;
    justify-content: center;
      color: white;
    background-color: transparent;
  /* font-family: Montserrat Regular; */
  font-weight: bold;
  font-size: 1em;
  position: relative;
  width: 100%;
  /* height: 7em; */
}
.middleDiv::after {
  /* content: ""; */
  width: 100%;
  height: 100%;
  position: absolute;
  background: repeating-linear-gradient(
    135deg,
    #222221,
    #222221 10px,
    transparent 10px,
    transparent 20px
  );
  opacity: 0.15;
}
.bottomDiv {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 6em;
  gap: 1em;

}
.bottomDiv .buttons {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: end;
  width: 100%;
  height: 3em;
  gap: 1rem;

}
.bottomDiv .buttonsdays {
  position: relative;
  display: flex;
  align-items: center;

  width: 100%;
  height: 3em;

  justify-content: center;
}
.yes,
.no {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 5em;
  height: 2em;
  /* margin: 0 1em 0 0; */
  background-color: #fff;
  color: #34D06E;
  /* font-family: Montserrat Regular; */
  font-weight: bold;
  font-size: 20px;
}

.yes:hover,
.no:hover {
  color: #34D06E;
}
.yes:hover span,
.no:hover span {
  animation: hoverText 1s ease-in-out infinite;
}
@keyframes hoverText {
  0% {
    opacity: 1;
  }
  50% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

.yes:hover .cornerBtn11,
.yes:hover .cornerBtn12,
.yes:hover .cornerBtn13,
.yes:hover .cornerBtn14 {
  opacity: 1;
}
.no:hover .cornerBtn21,
.no:hover .cornerBtn22,
.no:hover .cornerBtn23,
.no:hover .cornerBtn24 {
  opacity: 1;
}

.cornerBtn11,
.cornerBtn12,
.cornerBtn13,
.cornerBtn14,
.cornerBtn21,
.cornerBtn22,
.cornerBtn23,
.cornerBtn24,
.cornerCBtn1,
.cornerCBtn2,
.cornerCBtn3,
.cornerCBtn4 {
  position: absolute;
  width: 0.75em;
  height: 0.75em;
  background-color: transparent;
  margin: -0.45em;
  opacity: 0;
}
.cornerCBtn1,
.cornerCBtn2,
.cornerCBtn3,
.cornerCBtn4 {
  margin: -1em;
}
.cornerBtn11,
.cornerBtn21 {
  top: 0;
  left: 0;
  border-top: 2px solid #34D06E;
  border-left: 2px solid #34D06E;
}
.cornerBtn12,
.cornerBtn22 {
  top: 0;
  right: 0;
  border-top: 2px solid #34D06E;
  border-right: 2px solid #34D06E;
}
.cornerBtn13,
.cornerBtn23 {
  bottom: 0;
  left: 0;
  border-bottom: 2px solid #34D06E;
  border-left: 2px solid #34D06E;
}
.cornerBtn14,
.cornerBtn24 {
  bottom: 0;
  right: 0;
  border-bottom: 2px solid #34D06E;
  border-right: 2px solid #34D06E;
}
.cornerCBtn1 {
  top: 0;
  left: 0;
  border-top: 2px solid #34D06E;
  border-left: 2px solid #34D06E;
}
.cornerCBtn2 {
  top: 0;
  right: 0;
  border-top: 2px solid #34D06E;
  border-right: 2px solid #34D06E;
}
.cornerCBtn3 {
  bottom: 0;
  left: 0;
  border-bottom: 2px solid #34D06E;
  border-left: 2px solid #34D06E;
}
.cornerCBtn4 {
  bottom: 0;
  right: 0;
  border-bottom: 2px solid #34D06E;
  border-right: 2px solid #34D06E;
}

.clickSVG {
  /* fill: white; */
  text-align: center;
    width: 150px;
  height: 50px;
}
.open:hover .clickSVG {
  fill: #34D06E;
  animation: svgHover 0.5s ease-in-out infinite;
}
@keyframes svgHover {
  0% {
    scale: 1;
  }
  50% {
    scale: 0.95;
  }
  100% {
    scale: 1;
  }
}

.open:hover .cornerCBtn1,
.open:hover .cornerCBtn2,
.open:hover .cornerCBtn3,
.open:hover .cornerCBtn4 {
  opacity: 1;
}
.open:hover span {
  display: inline-block;
  animation: openSpanHover 0.75s ease infinite;
}
@keyframes openSpanHover {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(0.9);
  }
  100% {
    transform: scale(1);
  }
}

@keyframes openCorner1 {
  20% {
    transform: rotateY(180deg) rotateZ(0);
  }
  50% {
    top: 0;
    left: 50%;
  }
  51% {
    transform: rotateY(0deg);
  }
  100% {
    top: 0;
    left: 0;
    transform: rotateY(0deg);
  }
}
@keyframes openCorner2 {
  20% {
    transform: rotateY(180deg) rotateZ(0);
  }
  50% {
    top: 0;
    right: 50%;
  }
  51% {
    transform: rotateY(0deg);
  }
  100% {
    top: 0;
    right: 0;
    transform: rotateY(0deg);
  }
}
@keyframes openCorner3 {
  20% {
    transform: rotateY(180deg) rotateZ(0);
  }
  50% {
    bottom: 0;
    left: 50%;
  }
  51% {
    transform: rotateY(0deg);
  }
  100% {
    bottom: 0;
    left: 0;
    transform: rotateY(0deg);
  }
}
@keyframes openCorner4 {
  20% {
    transform: rotateY(180deg) rotateZ(0);
  }
  50% {
    bottom: 0;
    right: 50%;
  }
  51% {
    transform: rotateY(0deg);
  }
  100% {
    bottom: 0;
    right: 0;
    transform: rotateY(0deg);
  }
}
@keyframes closeCorner1 {
  0% {
    top: 0;
    left: 0;
    transform: rotateY(0deg) rotateZ(0);
  }
  50% {
    top: 0;
    left: 50%;
    transform: rotateY(180deg) rotateZ(0);
  }
  50.5% {
    transform: rotateY(180deg) rotateZ(0deg);
  }
  89% {
    transform: rotateY(180deg) rotateZ(0deg);
  }
  89.5% {
    transform: rotateY(180deg) rotateZ(180deg);
  }
  90% {
    left: 50%;
    top: 50%;
    transform: rotateY(180deg) rotateZ(180deg);
  }
  95% {
    transform: rotateY(0deg) rotateZ(180deg);
  }
  100% {
    transform: rotateY(0deg) rotateZ(180deg);
  }
}
@keyframes closeCorner2 {
  0% {
    top: 0;
    right: 0;
    transform: rotateY(0deg) rotateZ(0);
  }
  50% {
    top: 0;
    right: 50%;
    transform: rotateY(180deg) rotateZ(0);
  }
  50.5% {
    transform: rotateY(180deg) rotateZ(0deg);
  }
  89% {
    transform: rotateY(180deg) rotateZ(0deg);
  }
  89.5% {
    transform: rotateY(180deg) rotateZ(180deg);
  }
  90% {
    right: 50%;
    top: 50%;
    transform: rotateY(180deg) rotateZ(180deg);
  }
  95% {
    transform: rotateY(0deg) rotateZ(180deg);
  }
  100% {
    transform: rotateY(0deg) rotateZ(180deg);
  }
}
@keyframes closeCorner3 {
  0% {
    bottom: 0;
    left: 0;
    transform: rotateY(0deg) rotateZ(0);
  }
  50% {
    bottom: 0;
    left: 50%;
    transform: rotateY(180deg) rotateZ(0);
  }
  50.5% {
    transform: rotateY(180deg) rotateZ(0deg);
  }
  89% {
    transform: rotateY(180deg) rotateZ(0deg);
  }
  89.5% {
    transform: rotateY(180deg) rotateZ(180deg);
  }
  90% {
    left: 50%;
    bottom: 50%;
    transform: rotateY(180deg) rotateZ(180deg);
  }
  95% {
    transform: rotateY(0deg) rotateZ(180deg);
  }
  100% {
    transform: rotateY(0deg) rotateZ(180deg);
  }
}
@keyframes closeCorner4 {
  0% {
    bottom: 0;
    right: 0;
    transform: rotateY(0deg) rotateZ(0);
  }
  50% {
    bottom: 0;
    right: 50%;
    transform: rotateY(180deg) rotateZ(0);
  }
  50.5% {
    transform: rotateY(180deg) rotateZ(0deg);
  }
  89% {
    transform: rotateY(180deg) rotateZ(0deg);
  }
  89.5% {
    transform: rotateY(180deg) rotateZ(180deg);
  }
  90% {
    right: 50%;
    bottom: 50%;
    transform: rotateY(180deg) rotateZ(180deg);
  }
  95% {
    transform: rotateY(0deg) rotateZ(180deg);
  }
  100% {
    transform: rotateY(0deg) rotateZ(180deg);
  }
}
@keyframes openMiddleMenu {
  0% {
    background-color: transparent;
  }
  25% {
    background-color: grey;
  }
  50% {
    background-color: transparent;
  }
  75% {
    background-color: grey;
  }
  100% {
    background-color: transparent;
  }
}
@keyframes closeMiddleMenu {
  0% {
    background-color: transparent;
  }
  25% {
    background-color: grey;
  }
  50% {
    background-color: transparent;
  }
  75% {
    background-color: grey;
  }
  100% {
    background-color: transparent;
  }
}

@keyframes menuOpen {
  0% {
    opacity: 0;
    display: block;
  }
  49.5% {
    opacity: 0;
    display: block;
  }
  50% {
    opacity: 1;
    display: block;
  }
  100% {
    opacity: 1;
    display: block;
  }
}
@keyframes overlaystanderOpen {
  0% {
    display: none;
  }
  100% {
    display: block;
  }
}
@keyframes overlaystanderClose {
  0% {
    display: block;
  }
  100% {
    display: none;
  }
}
@keyframes menuWrapperClose {
  0% {
    display: block;
  }
  99% {
    display: block;
  }
  100% {
    display: none;
  }
}
@keyframes menuClose {
  0% {
    opacity: 1;
    display: block;
  }
  25% {
    opacity: 0;
    display: block;
  }
  40% {
    opacity: 0;
    display: block;
  }
  50% {
    opacity: 0;
    display: block;
  }
  75% {
    opacity: 0;
    display: none;
  }
  100% {
    opacity: 0;
    display: none;
  }
}

.open,
.yesCheck {
  z-index: 5;
}
.open:checked ~ .overlaystander {
  display: block;
}
.open:checked ~ .corner1 {
  animation: 0.35s openCorner1 linear forwards;
}
.open:checked ~ .corner2 {
  animation: 0.35s openCorner2 linear forwards;
}
.open:checked ~ .corner3 {
  animation: 0.35s openCorner3 linear forwards;
}
.open:checked ~ .corner4 {
  animation: 0.35s openCorner4 linear forwards;
}
.open:checked ~ .menuWrapper {
  display: block;
}
.open:checked ~ .menuWrapper .menu {
  animation: 0.45s menuOpen linear forwards;
}
.open:checked ~ .menuWrapper .menu .middleDiv {
  animation: 0.22s openMiddleMenu 0.11s linear forwards;
}

.yesCheck,
.noCheck {
  display: none;
}
.yesCheck:checked ~ .menuWrapper .menu .bottomDiv .buttons .no {
  opacity: 0;
}
.noCheck:checked ~ .menuWrapper .menu .bottomDiv .buttons .yes {
  opacity: 0;
}

.yesCheck:checked ~ .menuWrapper .menu .bottomDiv .buttons .yes {
  background-color: #166534;
  color: #22c55e;
}
.noCheck:checked ~ .menuWrapper .menu .bottomDiv .buttonsdays .yes {
  opacity: 0;
}

.yesCheck:checked ~ .menuWrapper .menu .bottomDiv .buttonsdays .yes {
  background-color: #166534;
  color: #22c55e;
}
.noCheck:checked ~ .menuWrapper .menu .bottomDiv .buttons .no {
  background-color: #166534;
  color: #22c55e;
}

.yesCheck:checked ~ .menuWrapper .menu .middleDiv,
.noCheck:checked ~ .menuWrapper .menu .middleDiv {
  animation: 0.25s closeMiddleMenu linear forwards;
}

.yesCheck:checked ~ .menuWrapper .corner1,
.noCheck:checked ~ .menuWrapper .corner1 {
  animation: 0.35s closeCorner1 linear forwards;
}
.yesCheck:checked ~ .menuWrapper .corner2,
.noCheck:checked ~ .menuWrapper .corner2 {
  animation: 0.35s closeCorner2 linear forwards;
}
.yesCheck:checked ~ .menuWrapper .corner3,
.noCheck:checked ~ .menuWrapper .corner3 {
  animation: 0.35s closeCorner3 linear forwards;
}
.yesCheck:checked ~ .menuWrapper .corner4,
.noCheck:checked ~ .menuWrapper .corner4 {
  animation: 0.35s closeCorner4 linear forwards;
}

.yesCheck:checked ~ .menuWrapper,
.noCheck:checked ~ .menuWrapper {
  animation: 0.4s menuWrapperClose linear forwards;
}
.yesCheck:checked ~ .menuWrapper .menu,
.noCheck:checked ~ .menuWrapper .menu {
  animation: 0.4s menuClose 0.1s step-end forwards;
}
.yesCheck:checked ~ .overlaystander,
.noCheck:checked ~ .overlaystander {
  animation: 0.4s overlaystanderClose linear forwards;
}


</style>


@endpush


<!--xxxxxx-->
<div class="max-w-5xl mx-auto">
  <!-- Tabs for Calorie Categories -->
 <div class="flex flex-wrap justify-center border-b border-gray-300 mb-6" id="plans">
    <button id="level-1400"
            class="tab-btn py-2 px-6 text-lg font-bold active"
            data-tab="1400">
      Weight Loss <br> 1300 - 1500kcal
    </button>
    <button id="level-1700"
            class="tab-btn py-2 px-6 text-lg font-bold"
            data-tab="1700">
      Fitness <br> 1600 - 1800kcal
    </button>
    <button id="level-2000"
            class="tab-btn py-2 px-6 text-lg font-bold"
            data-tab="2000">
      Weight Gain <br> 1900 - 2200kcal
    </button>
  </div>

  <!-- الكروت -->
  <div class="flex justify-center gap-4 mb-6" id="card-container">



<div class="scene "  data-type="customize" onclick="handleCustomize(this)">
  <div class="cube">
    <span class="side top"  >Customize</span>
    <span class="side front">Customize</span>
  </div>
</div>

<div class="scene" data-type="stander" onclick="handleStander(this)">
  <div class="cube" >
    <span class="side top">Standard</span>
    <span class="side front">Standard</span>
  </div>
</div>
  </div>

  <!-- التاب الخارجي -->
  <div class="tab-content   " id="main-meal-tab">
    <div class="tab-content">
      <!-- 1400kcal Tab -->
      <div id="tab-1400" class="tab-pane 1400xxxx">
        <h2 class="text-xl font-bold text-center mb-4">Choose Your Subscription</h2>
        <div id="stander-plan-length-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 max-w-3xl mx-auto">
          @if($threeWeekUiEnabled ?? false)
            <button
              type="button"
              class="plan-length-card border rounded-2xl px-5 py-4 text-left transition-all hover:bg-green-600 hover:text-white"
              data-weeks="3">
              <span class="block text-lg font-bold">3 Weeks</span>
              <span class="block text-sm opacity-80">18 Days or 15 Days</span>
            </button>
          @endif
          <button
            type="button"
            class="plan-length-card border rounded-2xl px-5 py-4 text-left bg-green-600 text-white transition-all"
            data-weeks="4">
            <span class="block text-lg font-bold">Monthly</span>
            <span class="block text-sm opacity-80">24 Days or 20 Days</span>
          </button>
        </div>
        <h2 class="text-xl font-bold text-center mb-4">View a Week</h2>
        <div class="flex flex-wrap justify-center mb-4" id="stander-weeks-container">
          <button class="tap-week py-1 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600"
            week-duration="1">
            1st week
          </button>
          <button class="tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="2">
            2nd week
          </button>
          <button class="tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="3">
            3rd week
          </button>
          <button class="tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="4">
            4th week
          </button>
        </div>
        <h2 class="text-xl font-bold text-center mb-4">Choose Delivery Days</h2>
        <div id="stander-duration-container" class="flex flex-wrap justify-center mb-4">
          <button  class="duration-btn py-1 px-4 mx-2 border rounded-full bg-green-600 text-white position-relative "
            data-duration="6" id="1st_6day">
            <div class="duration-icon  number-test-duration-6   position-absolute text-white bg-green-600 px-1 border rounded-full"
              style="top:-22px;left: 0;"> ✔</div>
            <span class="duration-label-text" data-days="6">24 Days</span>
          </button>
          <button class="duration-btn py-1 px-4 mx-2 border rounded-full hover:bg-green-600 position-relative"
            data-duration="5">
            <div
              class=" duration-icon  number-test-duration-5  hidden position-absolute text-white bg-green-600 px-1 border rounded-full"
              style="top:-22px;left: 0;"> ✔ </div>
            <span class="duration-label-text" data-days="5">20 Days</span>
          </button>
        </div>
        <div class="days-content text-center">
          <h3 class="text-lg font-bold text-center mb-4">Select a Day</h3>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 justify-center ">
            <button class="day-option py-2 border rounded bg-green-600 text-white position-relative  "
              data-day="saturday" id="1st_day">
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full  "
                style="top:-22px;left: 0;">✔</div> Saturday
            </button>
            <button class="day-option py-2 border rounded position-relative" data-day="sunday">
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;">✔</div> Sunday
            </button>
            <button class="day-option py-2 border rounded position-relative" data-day="monday">
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;">✔</div> Monday
            </button>
            <button class="day-option py-2 border rounded position-relative" data-day="tuesday">
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;">✔</div> Tuesday
            </button>
            <button class="day-option py-2 border rounded position-relative" data-day="wednesday">
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;">✔</div> Wednesday
            </button>
            <button class="day-option py-2 border rounded position-relative" data-day="thursday">
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;">✔</div> Thursday
            </button>
          </div>
        </div>
        <div class="meals-content mt-6">
          <h3 class="text-lg font-bold text-center mb-4">Select a Meal</h3>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 justify-center">
            <button class="meal-btn py-2 border rounded bg-green-600 text-white position-relative" data-meal="breakfast"
              id="1st_meal">
              <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;"> ✔</div>Breakfast
            </button>
            <button class="meal-btn py-2 border rounded position-relative  " data-meal="lunch">
              <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;"> ✔</div>Lunch
            </button>
            <button class="meal-btn py-2 border rounded position-relative" data-meal="dinner">
              <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;"> ✔</div>Dinner
            </button>
            <button class="meal-btn py-2 border rounded position-relative" data-meal="snack1">
              <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;"> ✔</div>Snack 1
            </button>
            <button class="meal-btn py-2 border rounded position-relative" data-meal="snack2">
              <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;"> ✔</div>Snack 2
            </button>
          </div>
          <div id="stander-meal-accordion"
            class="meal-section grid grid-cols-1 sm:grid-cols-3 md:grid-cols-2 gap-4 justify-center meal-options-accordion">
          </div>
        </div>
      </div>
    </div>


  <div id="standerConfirmOverlay" class="overlaystander"></div>

  <div id="standerConfirmModal" class="menuWrapper">
    <div class="corner1"></div>
    <div class="corner2"></div>
    <div class="corner3"></div>
    <div class="corner4"></div>
    <div class="menu">


      <div class="middleDiv">
        <span style=" padding: 1em; background-color: #fff;     box-shadow: 0 4px 8px rgba(0, 255, 0, 0.3);
    text-shadow: 0 7px 10px rgba(0, 255, 0, 0.5); text-align: center;  font-size: x-large;  color: #34D06E;"
          >Are you sure you want to choose this option?</span
        >
      </div>

      <div class="bottomDiv">
        <div class="buttonsdays">
          <label class="yes" for="yesCheck">
            <input class="yesCheck" type="radio" />
            <span>Yes</span>
            <div class="cornerBtn11"></div>
            <div class="cornerBtn12"></div>
            <div class="cornerBtn13"></div>
            <div class="cornerBtn14"></div>
          </label>

        </div>
      </div>
    </div>
  </div>



    <div class="main">


  <input hidden="" class="yesCheck" id="yesCheck" name="toggle" type="radio" />
  <input hidden="" class="noCheck" id="noCheck" name="toggle" type="radio" />
  <input hidden="" class="open" name="toggle" type="radio" id="openMenu" />

  <div class="menuOpenBtns">


    <div class="selectionBtns">
      <label class="open" for="openMenu">
     <div class="clickSVG meal-btn py-2 border rounded bg-green-600 text-white position-relative"> <h1 class="text-white text-center">save</h1></div>
        <div class="cornerCBtn1"></div>
        <div class="cornerCBtn2"></div>
        <div class="cornerCBtn3"></div>
        <div class="cornerCBtn4"></div>
      </label>

    </div>
  </div>

  <div  class="overlaystander"></div>

  <div class="menuWrapper">
    <div class="corner1"></div>
    <div class="corner2"></div>
    <div class="corner3"></div>
    <div class="corner4"></div>
    <div class="menu">


      <div class="middleDiv">
        <span style=" padding: 1em; background-color: #fff;  box-shadow: 0 4px 8px rgba(0, 255, 0, 0.3);
    text-shadow: 0 7px 10px rgba(0, 255, 0, 0.5);  font-size: x-large;  color: #34D06E;"
          >Are you sure you want to  choose this option?</span
        >
      </div>

      <div class="bottomDiv">
        <div class="buttons">
          <label id="standerConfirmYes" class="yes" for="yesCheck">
            <input class="yesCheck" type="radio" />
            <span>Yes</span>
            <div class="cornerBtn11"></div>
            <div class="cornerBtn12"></div>
            <div class="cornerBtn13"></div>
            <div class="cornerBtn14"></div>
          </label>
          <label class="no" for="noCheck">
            <input class="noCheck" type="radio" />
            <span>No</span>
            <div class="cornerBtn21"></div>
            <div class="cornerBtn22"></div>
            <div class="cornerBtn23"></div>
            <div class="cornerBtn24"></div>
          </label>
        </div>
      </div>
    </div>
  </div>


</div>
  </div>

<!-- ============================= -->
<!-- Blok Customize (نسخة من الـ Stander) -->

<div class="tab-content hidden" id="main-meal-tab-customize">
    <div class="tab-content">
      <!-- 1400kcal Tab -->
      <div id="tab-1400" class="tab-pane 1400xxxx">
        <h2 class="text-xl font-bold text-center mb-4">Select a Weeks</h2>
        <div class="flex flex-wrap justify-center mb-4">
          <button class="tap-week py-1 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600"
            week-duration="1">
            1 weeks
          </button>
          <button class="tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="2">
            2 weeks
          </button>
          <button class="tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="3">
            3 weeks
          </button>
          <button class="tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="4">
            4 weeks
          </button>
        </div>
        <h2 class="text-xl font-bold text-center mb-4">Select Duration</h2>
        <div class="flex flex-wrap justify-center mb-4">
          <button class="duration-btn py-1 px-4 mx-2 border rounded-full bg-green-600 text-white position-relative"
            data-duration="6" id="1st_6day">
            <div class=" number-test-duration-6   position-absolute text-white bg-green-600 px-1 border rounded-full"
              style="top:-22px;left: 0;"> 0/6</div> 24 Days
          </button>
          <button class="duration-btn py-1 px-4 mx-2 border rounded-full hover:bg-green-600 position-relative"
            data-duration="5">
            <div
              class=" number-test-duration-5  hidden position-absolute text-white bg-green-600 px-1 border rounded-full"
              style="top:-22px;left: 0;"> 0/5 </div> 20 Days
          </button>
        </div>
  <div class="days-content text-center mb-4">
    <h3 class="text-lg font-bold mb-4">Select a Day (Customize)</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 justify-center">

        <button class="day-option-customize py-2 border rounded bg-green-600 text-white position-relative  "
              data-day="saturday" >
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full  "
                style="top:-22px;left: 0;">0/5</div> Saturday
            </button>
            <button class="day-option-customize py-2 border rounded position-relative" data-day="sunday">
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;">0/5</div> Sunday
            </button>
            <button class="day-option-customize py-2 border rounded position-relative" data-day="monday">
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;">0/5</div> Monday
            </button>
            <button class="day-option-customize py-2 border rounded position-relative" data-day="tuesday">
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;">0/5</div> Tuesday
            </button>
            <button class="day-option-customize py-2 border rounded position-relative" data-day="wednesday">
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;">0/5</div> Wednesday
            </button>
            <button class="day-option-customize py-2 border rounded position-relative" data-day="thursday">
              <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                style="top:-22px;left: 0;">0/5</div> Thursday
            </button>
    </div>
  </div>
  <div class="meals-content text-center mb-4">
    <h3 class="text-lg font-bold mb-4">Select a Meal (Customize)</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
      <button class="meal-btn-customize position-relative py-2 border rounded" data-meal="breakfast">  <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full hidden"
              style="top:-22px;left: 0;"> ✔</div>
        Breakfast
      </button>
      <button class="meal-btn-customize position-relative py-2 border rounded" data-meal="lunch"> <div class=" number-test-Meal  position-absolute text-white bg-green-600 px-1 border rounded-full hidden"
              style="top:-22px;left: 0;"> ✔</div>
        Lunch
      </button>
      <button class="meal-btn-customize position-relative py-2 border rounded" data-meal="dinner"> <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full hidden"
              style="top:-22px;left: 0;"> ✔</div>
        Dinner
      </button>
      <button class="meal-btn-customize position-relative py-2 border rounded" data-meal="snack1"> <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full hidden"
              style="top:-22px;left: 0;"> ✔</div>
        Snack 1
      </button>
      <button class="meal-btn-customize position-relative py-2 border rounded" data-meal="snack2"> <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full hidden"
              style="top:-22px;left: 0;"> ✔</div>
        Snack 2
      </button>
    </div>
  </div>
    <div id="meal-options-accordion-customize"
         class="meal-section grid grid-cols-1 sm:grid-cols-3 md:grid-cols-2 gap-4 justify-center"></div>
      </div>
    </div>





<div id="customizeConfirmOverlay" class="overlaystander"></div>
<div id="customizeConfirmMenuWrapper" class="menuWrapper">
  <div class="corner1"></div>
  <div class="corner2"></div>
  <div class="corner3"></div>
  <div class="corner4"></div>
  <div class="menu">
    <div class="middleDiv">
      <span style="padding: 1em;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 255, 0, 0.3);
    text-shadow: 0 7px 10px rgba(0, 255, 0, 0.5);
    text-align: center;
    font-size: x-large;
    color: #34D06E;
    "></span>
    </div>
    <div class="bottomDiv">
      <div class="buttons" style="    justify-content: center;">
        <label class="yes"><span>Yes</span>
          <div class="cornerBtn11"></div>
          <div class="cornerBtn12"></div>
          <div class="cornerBtn13"></div>
          <div class="cornerBtn14"></div>
        </label>

      </div>
    </div>
  </div>
</div>









    <button class="save-button mb-5 w-full py-2 border rounded bg-green-600 text-white mt-6 sideMenuToggler">
      Save
    </button>
  </div>
<!-- ============================= -->


</div>
@push('extra-js')
<script src="https://cdn.tailwindcss.com"></script>



<script>
  window.plansPricingCatalog = @json($plansPricingCatalog ?? []);
  window.threeWeekUiEnabled = @json($threeWeekUiEnabled ?? false);
  window.subscriptionVatRate = @json($vatRate ?? 0.14);

  // ====== Stander-only Script ======

  let selectedLevel               = '1400';
  let currentStanderWeekData      = null;
  let selectedStanderDurationDays = 6;
  let selectedStanderDurationWeeks = 4;
  let selectedStanderWeekCount    = 1;
  let selectedLanguage            = 'en';
  let selectedStanderDay          = 'saturday';
  let selectedStanderMeal         = 'breakfast';

  function clearCardActive(element) {
    document.querySelectorAll('.cube').forEach(cube => cube.classList.remove('active'));
    element.querySelector('.cube').classList.add('active');
  }

  function resolvePlansTier(level, weeksCount, daysPerWeek) {
    const planCatalog = window.plansPricingCatalog?.[String(level)]
      || window.plansPricingCatalog?.[Number(level)];
    const weeksCatalog = planCatalog?.[String(weeksCount)]
      || planCatalog?.[Number(weeksCount)];

    return weeksCatalog?.[String(daysPerWeek)]
      || weeksCatalog?.[Number(daysPerWeek)]
      || null;
  }

  function resolveSubscriptionSubtotal(level, daysPerWeek = selectedStanderDurationDays, weeksCount = selectedStanderDurationWeeks) {
    const tier = resolvePlansTier(level, weeksCount, daysPerWeek);

    if (!tier) {
      throw new Error(`Unsupported subscription pricing combination: ${level} calories / ${weeksCount} weeks / ${daysPerWeek} days.`);
    }

    return Number(tier.without_vat || 0);
  }

  function getDurationLabel(daysPerWeek = selectedStanderDurationDays, weeksCount = selectedStanderDurationWeeks) {
    if (weeksCount === 3) {
      return daysPerWeek === 6 ? '18 Days' : '15 Days';
    }

    return daysPerWeek === 6 ? '24 Days' : '20 Days';
  }

  function getDurationConfirmation(daysPerWeek = selectedStanderDurationDays, weeksCount = selectedStanderDurationWeeks) {
    const durationLabel = getDurationLabel(daysPerWeek, weeksCount);

    return daysPerWeek === 6
      ? `Are you sure you want to choose <br/> <span>${durationLabel}</span>   <br/> Friday will be off only ?`
      : `Are you sure you want to choose <br/> <span>${durationLabel}</span>   <br/> <span>Friday and Saturday</span> <br/> will be off only ?`;
  }

  function syncCurrentWeekData() {
    currentStanderWeekData = window._allStanderData?.[selectedLevel]?.[`week${selectedStanderWeekCount}`] || null;
  }

  function refreshPlanLengthCards() {
    document.querySelectorAll('#stander-plan-length-container .plan-length-card').forEach((btn) => {
      const isActive = Number(btn.dataset.weeks) === selectedStanderDurationWeeks;

      btn.classList.toggle('bg-green-600', isActive);
      btn.classList.toggle('text-white', isActive);
    });
  }

  function refreshDurationLabels() {
    document.querySelectorAll('#stander-duration-container .duration-label-text').forEach((label) => {
      const days = Number(label.dataset.days || 0);
      label.textContent = getDurationLabel(days, selectedStanderDurationWeeks);
    });
  }

  function refreshWeekButtons() {
    const weekBtns = document.querySelectorAll('#stander-weeks-container .tap-week');

    if (selectedStanderWeekCount > selectedStanderDurationWeeks) {
      selectedStanderWeekCount = 1;
    }

    weekBtns.forEach((btn) => {
      const weekNumber = Number(btn.getAttribute('week-duration') || 0);
      const isVisible = weekNumber <= selectedStanderDurationWeeks;
      const isActive = isVisible && weekNumber === selectedStanderWeekCount;

      btn.classList.toggle('hidden', !isVisible);
      btn.classList.toggle('bg-green-600', isActive);
      btn.classList.toggle('text-white', isActive);
    });

    syncCurrentWeekData();
  }

  function applyPlanLength(weeksCount) {
    if (!window.threeWeekUiEnabled && weeksCount === 3) {
      return;
    }

    selectedStanderDurationWeeks = weeksCount;
    selectedStanderMeal = 'breakfast';
    selectedStanderDay = selectedStanderDurationDays === 5 ? 'sunday' : 'saturday';

    refreshPlanLengthCards();
    refreshDurationLabels();
    refreshWeekButtons();
    toggleSaturday();
    setInitialActivesStander();
    renderMealsStander();
  }

  function bindPlanLengthControls() {
    document.querySelectorAll('#stander-plan-length-container .plan-length-card').forEach((btn) => {
      btn.onclick = () => {
        const weeksCount = Number(btn.dataset.weeks || 4);

        if (weeksCount === selectedStanderDurationWeeks) {
          return;
        }

        applyPlanLength(weeksCount);
      };
    });
  }

  function toggleSaturday() {
    const satBtn = document.querySelector('#tab-1400 .day-option[data-day="saturday"]');
    if (!satBtn) return;
    if (selectedStanderDurationDays === 5) satBtn.classList.add('hidden');
    else                                   satBtn.classList.remove('hidden');
  }

  function bindStanderWeekControls() {
    const weekBtns = document.querySelectorAll('#stander-weeks-container .tap-week');
    weekBtns.forEach(btn => {
      btn.onclick = () => {
        const nextWeekCount = Number(btn.getAttribute('week-duration') || 0);

        if (nextWeekCount > selectedStanderDurationWeeks) {
          return;
        }

        selectedStanderWeekCount = nextWeekCount;
        refreshWeekButtons();

        selectedStanderMeal = 'breakfast';
        selectedStanderDay  = (selectedStanderDurationDays === 5 ? 'sunday' : 'saturday');

        toggleSaturday();
        setInitialActivesStander();
        renderMealsStander();
      };
    });
  }

  function bindStanderDurationControls() {
  const container = document.getElementById('stander-duration-container');
  const btns      = container.querySelectorAll('.duration-btn');
  const overlay   = document.getElementById('standerConfirmOverlay');
  const modal     = document.getElementById('standerConfirmModal');
  const msgSpan   = modal.querySelector('.middleDiv span');

  overlay.style.display = 'none';
  modal.style.display   = 'none';

  modal._currentBtn = null;

  if (!modal.dataset.bound) {
    modal.addEventListener('click', e => {
      if (e.target.closest('.yes')) {
        overlay.style.display = modal.style.display = 'none';
        if (modal._currentBtn) applyDuration(modal._currentBtn);
        return;
      }

      if (e.target.closest('.no')) {
        overlay.style.display = modal.style.display = 'none';
      }
    });

    modal.dataset.bound = 'true';
  }

  btns.forEach(btn => {
    btn.onclick = () => {
      if (btn.classList.contains('bg-green-600')) return;

      const days = Number(btn.dataset.duration || 0);
      msgSpan.innerHTML = getDurationConfirmation(days, selectedStanderDurationWeeks);

      modal._currentBtn = btn;
      overlay.style.display = modal.style.display = 'block';
    };
  });
}

function applyDuration(btn) {
  const btns = document.querySelectorAll('#stander-duration-container .duration-btn');
  btns.forEach(b => {
    b.classList.remove('bg-green-600','text-white');
    b.querySelector('.duration-icon')?.classList.add('hidden');
  });

  btn.classList.add('bg-green-600','text-white');
  btn.querySelector('.duration-icon')?.classList.remove('hidden');

  selectedStanderDurationDays = +btn.dataset.duration;
  selectedStanderMeal = 'breakfast';
  selectedStanderDay  = selectedStanderDurationDays === 5 ? 'sunday' : 'saturday';

  refreshDurationLabels();
  toggleSaturday();
  setInitialActivesStander();
  renderMealsStander();
}

  function bindStanderControls() {
    document.querySelectorAll('#tab-1400 .day-option')
      .forEach(btn => {
        btn.onclick = () => {
          document.querySelectorAll('#tab-1400 .day-option')
            .forEach(b => b.classList.remove('bg-green-600','text-white'));
          btn.classList.add('bg-green-600','text-white');
          selectedStanderDay = btn.dataset.day;
          renderMealsStander();
        };
      });

    document.querySelectorAll('#tab-1400 .meal-btn')
      .forEach(btn => {
        btn.onclick = () => {
          document.querySelectorAll('#tab-1400 .meal-btn')
            .forEach(b => {
              b.classList.remove('bg-green-600','text-white');
              b.querySelector('.meal-icon')?.classList.add('hidden');
            });
          btn.classList.add('bg-green-600','text-white');
          btn.querySelector('.meal-icon')?.classList.remove('hidden');

          selectedStanderMeal = btn.dataset.meal;
          renderMealsStander();
        };
      });
  }

  function setInitialActivesStander() {
    document.querySelectorAll('#tab-1400 .day-option').forEach(btn => {
      const isActive = btn.dataset.day === selectedStanderDay;
      btn.classList.toggle('bg-green-600', isActive);
      btn.classList.toggle('text-white',    isActive);
    });
    document.querySelectorAll('#tab-1400 .meal-btn').forEach(btn => {
      const isActive = btn.dataset.meal === selectedStanderMeal;
      btn.classList.toggle('bg-green-600', isActive);
      btn.classList.toggle('text-white',    isActive);
      btn.querySelector('.meal-icon')?.classList.toggle('hidden', !isActive);
    });
  }

function renderMealsStander() {
  const container = document.getElementById('stander-meal-accordion');
  container.innerHTML = '';

  const dayBlock  = (currentStanderWeekData || {})[selectedStanderDay] || {};
  const mealBlock = (dayBlock[selectedStanderMeal]   || {});
  const arr       = mealBlock[selectedLanguage]      || [];

  if (!arr.length) {
    container.innerHTML = '<p class="text-center text-gray-500">لا توجد وجبات</p>';
    return;
  }

  arr.forEach(item => {
    const card = document.createElement('div');
    card.className = 'meal-option mb-2 rounded p-4 meal-active selected-meal';
    card.innerHTML = `
  <img src="./cart.gif" alt="${item.title}" class="meal-image w-full h-auto rounded mb-2 " />
      <div class="meal-desc content">
        <h4 class="font-bold text-lg mb-2">${item.title}</h4>
        <p class="text-gray-600">${item.description}</p>
        <p class="text-sm mb-1">${item.description}</p>
        <p class="text-green-600 font-semibold">${item.price} $</p>
      </div>
    `;

    // ——— only open & populate dialog, never mutating the page container ———
    card.addEventListener('click', () => {
      // 1) make sure the dialog exists
      let dlg = document.querySelector('dialog.meal-dialog');
      if (!dlg) {
        dlg = document.createElement('dialog');
        dlg.className = 'meal-dialog rounded-lg shadow-lg p-4 border min-h-80';
        dlg.innerHTML = `
          <div class="dialog-container">
            <button class="close-dialog">×</button>
            <form class="form-horizontal" id="ModalForm">
              <div class="modal-body">
                <input type="number" hidden value="1" id="myBox" />
                <div id="dialogMealsSection"></div>
              </div>
              <div class="submit-menu-btn-div">
                <button type="button" class="menu-submit" id="okBtn">OK</button>
              </div>
            </form>
          </div>
        `;
        document.body.appendChild(dlg);
      }

      // 2) clear out just the dialog’s list
      const dialogList = dlg.querySelector('#dialogMealsSection');
      dialogList.innerHTML = '';

      // 3) fetch what’s already selected so we can pre-check it
      const existing = JSON.parse(localStorage.getItem('meals'))?.[selectedLevel]?.[selectedStanderDay] || {};

      // 4) fill it
      arr.forEach(m => {
        const isChecked = Object.values(existing).includes(m.id) ? 'checked' : '';
        dialogList.insertAdjacentHTML('beforeend', `
          <div class="meals" data-meal-id="${m.id}" style="margin-bottom:1em;">
            <div class="circle">-</div>
            <div class="meal-data">
              <img class="dialog-image rounded-lg" src="${m.image}" alt="${m.title}" width="150" height="150"/>
              <div class="meal-info">
                <h4>${m.title}</h4>
                 <div class="meal-macros">
                    <div class="macros">
                                        <div class="cal">
                                          <input class="num Kcal" value="60" readonly style="border: none; text-align: center" />
                                          <div class="title">kcal</div>
                                        </div>
                                        <div class="cal">
                                          <input class="num fat" value="1.1" readonly style="border: none; text-align: center" />
                                          <div class="title">fat</div>
                                        </div>
                                        <div class="cal">
                                          <input class="num protein" value="4.1" readonly style="border: none; text-align: center" />
                                          <div class="title">protein</div>
                                        </div>
                                        <div class="cal">
                                          <input class="num carb" value="7.9" readonly style="border: none; text-align: center" />
                                          <div class="title">carb</div>
                                        </div>
                                      </div>
                 </div>
                <p>${m.description}</p>
               <p class="text-green-600 font-semibold">${item.price} $</p>
              </div>
            </div>
          </div>
        `);
      });



      // 6) wire up close
      dlg.querySelector('.close-dialog').onclick = () => dlg.close();
      dlg.querySelector('#okBtn').onclick       = () => dlg.close();

      // 7) finally show it
      dlg.showModal();
    });

    container.appendChild(card);
  });
}

  function handleStander(card) {
    clearCardActive(card);
    card.classList.add('active');
    document.getElementById('main-meal-tab').classList.remove('hidden');
    document.getElementById('main-meal-tab-customize').classList.add('hidden');

    fetch('/standred_weeks.json')
      .then(r => r.ok ? r.json() : Promise.reject(r.statusText))
      .then(data => {
        window._allStanderData = data;
        selectedStanderMeal = 'breakfast';

        bindPlanLengthControls();
        toggleSaturday();
        bindStanderWeekControls();
        bindStanderDurationControls();
        bindStanderControls();
        refreshPlanLengthCards();
        refreshDurationLabels();
        refreshWeekButtons();
        setInitialActivesStander();
        renderMealsStander();
      })
      .catch(console.error);
  }

  document.querySelectorAll('#plans .tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#plans .tab-btn')
        .forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      selectedLevel = btn.dataset.tab;
      handleStander(document.querySelector('[data-type="stander"]'));
    });
  });

  document.querySelector('[data-type="stander"]')
    .addEventListener('click', function() { handleStander(this); });

  window.addEventListener('DOMContentLoaded', () => {
    refreshPlanLengthCards();
    refreshDurationLabels();
    refreshWeekButtons();
    handleStander(document.querySelector('[data-type="stander"]'));
  });



   // ==== Global Variables ====
  let currentCustomizeData  = null;
  let selectedCustomizeDay  = 'saturday';
  let selectedCustomizeMeal = 'breakfast';
  let weekDuration          = 1;
  let dayDuration           = 6;


  // ==== Clear “active” on cards ====


  // ==== Main entrypoint for Customize ====
  async function handleCustomize(elem) {
    clearCardActive(elem);
    elem.classList.add('active');

    // show Customize tab, hide Stander tab
    document.getElementById('main-meal-tab').classList.add('hidden');
    document.getElementById('main-meal-tab-customize').classList.remove('hidden');

    try {
      const res = await fetch('/data.json');
      if (!res.ok) throw new Error(res.statusText);
      const all = await res.json();
      console.log('Fetched Customize Data:', all);

      // pick current level
      currentCustomizeData = all[selectedLevel];

      // reset defaults
      selectedCustomizeDay  = 'saturday';
      selectedCustomizeMeal = 'breakfast';
      weekDuration          = 1;
      dayDuration           = 6;

      setInitialActivesCustomize();
      bindCustomizeWeekControls();
      bindCustomizeDurationControls();
      bindCustomizeControls();
      renderMealsCustomize();
       updateSelectedMealCard();
  updateCustomizeDayCounts();
  updateCustomizeDurationCount()


    } catch(err) {
      console.error('Error in handleCustomize:', err);
    }
  }

  // ==== Bind “weeks” buttons (1–4 weeks) ====
  function bindCustomizeWeekControls() {
    document
    .querySelectorAll('#main-meal-tab-customize .tap-week')
    .forEach(btn => {
      btn.onclick = () => {
        // إزالة الـ active من كل الأزرار
        document
          .querySelectorAll('#main-meal-tab-customize .tap-week')
          .forEach(b => b.classList.remove('bg-green-600','text-white'));

        // تفعيل الزر المضغوط
        btn.classList.add('bg-green-600','text-white');

        // سجّل رقم الأسبوع فقط
        weekDuration = +btn.getAttribute('week-duration');
        console.log('Selected week duration:', weekDuration);
      };
    });
  }

  // ==== Bind “duration” buttons (5/6 days) ====
function bindCustomizeDurationControls() {
  const btns       = document.querySelectorAll('#main-meal-tab-customize .duration-btn');
  const overlay    = document.getElementById('customizeConfirmOverlay');
  const modal      = document.getElementById('customizeConfirmMenuWrapper');
  const msgSpan    = modal.querySelector('.middleDiv span');
  const saturdayBtn= document.querySelector(
    '#main-meal-tab-customize .day-option-customize[data-day="saturday"]'
  );
  let currentBtn   = null;

  // إخفاء افتراضي
  overlay.classList.remove('active');
  modal.classList.remove('active');

  // التقاط Yes/No
  modal.addEventListener('click', e => {
    if (e.target.closest('.yes')) {
      overlay.classList.remove('active');
      modal.classList.remove('active');
      applyCustomizeDuration(currentBtn);
    }
    if (e.target.closest('.no')) {
      overlay.classList.remove('active');
      modal.classList.remove('active');
    }
  });

  // ربط أزرار المدة
  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      if (btn.classList.contains('bg-green-600')) return; // لا نعيد التأكيد على زرّ مفعل
      const days = btn.dataset.duration;
      msgSpan.innerHTML = days === '6'
       ? 'Are you sure you want to choose <br/> <span  >24 Days</span>   <br/> Friday will be off only  ?'
        : 'Are you sure you want to choose <br/> <span  >20 Days</span>   <br/> <span >Friday and Saturday</span> <br/> will be off only ?';
      currentBtn = btn;
      overlay.classList.add('active');
      modal.classList.add('active');
    });
  });

  // تطبيق التغيير بعد الموافقة
  function applyCustomizeDuration(btn) {
    // 1) تنظيف كل الأزرار
    btns.forEach(b => {
      b.classList.remove('bg-green-600','text-white');
      b.querySelector('.number-test-duration-6')?.classList.add('hidden');
      b.querySelector('.number-test-duration-5')?.classList.add('hidden');
    });

    // 2) فعل الزرّ المختار
    btn.classList.add('bg-green-600','text-white');
    const days = btn.dataset.duration;
    btn.querySelector(days === '6'
      ? '.number-test-duration-6'
      : '.number-test-duration-5'
    )?.classList.remove('hidden');

    // 3) خزّن المتغيرات
    dayDuration = parseInt(days,10);
    selectedCustomizeMeal = 'breakfast';

    // 4) إخفاء/إظهار زرّ السبت
    if (dayDuration === 5) saturdayBtn.classList.add('hidden');
    else                    saturdayBtn.classList.remove('hidden');

    // 5) اضغط برمجيًا على الزرّ
    const dayToActivate = dayDuration === 5 ? 'sunday' : 'saturday';
    selectedCustomizeDay = dayToActivate;
    const dayBtn = document.querySelector(
      `#main-meal-tab-customize .day-option-customize[data-day="${dayToActivate}"]`
    );
    if (dayBtn) dayBtn.click();
  }
}

function applyCustomizeDuration(btn) {
  // 1) reset كل الأزرار
  const btns = document.querySelectorAll('#main-meal-tab-customize .duration-btn');
  btns.forEach(b => {
    b.classList.remove('bg-green-600','text-white');
    const icon = b.querySelector('.duration-icon');
    if (icon) icon.classList.add('hidden');
  });

  // 2) فعل الزرّ المختار وأظهر الـ icon
  btn.classList.add('bg-green-600','text-white');
  btn.querySelector('.duration-icon')?.classList.remove('hidden');

  // 3) خزّن المتغيّرات
  dayDuration           = +btn.dataset.duration;
  selectedCustomizeDay  = dayDuration === 5 ? 'sunday' : 'saturday';
  selectedCustomizeMeal = 'breakfast';

  // 4) إخفاء/إظهار زرّ السبت
  const satBtn = document.querySelector(
    '#main-meal-tab-customize .day-option-customize[data-day="saturday"]'
  );
  if (dayDuration === 5) satBtn.classList.add('hidden');
  else                  satBtn.classList.remove('hidden');

  // 5) تحديث الستايلات والعدادات
  setInitialActivesCustomize();
  updateCustomizeDayCounts();
  updateCustomizeDurationCount()
  renderMealsCustomize();
}

function applyCustomizeDuration(btn) {
  const btns = document.querySelectorAll('#main-meal-tab-customize .duration-btn');
  btns.forEach(b => {
    b.classList.remove('bg-green-600','text-white');
    b.querySelector('.duration-icon')?.classList.add('hidden');
  });
  btn.classList.add('bg-green-600','text-white');
  btn.querySelector('.duration-icon')?.classList.remove('hidden');

  // خزّن المتغيرات ثم reset اليوم/الوجبة
  dayDuration           = +btn.dataset.duration;
  selectedCustomizeDay  = dayDuration === 5 ? 'sunday' : 'saturday';
  selectedCustomizeMeal = 'breakfast';

  // حدّث الواجهة
setInitialActivesCustomize();
      bindCustomizeWeekControls();
      updateCustomizeDurationCount()
      renderMealsCustomize();
       updateSelectedMealCard();
  updateCustomizeDayCounts();
}


  // ==== Bind day & meal buttons ====
function bindCustomizeControls() {
  document.querySelectorAll('#main-meal-tab-customize .day-option-customize')
    .forEach(btn => {
      btn.onclick = () => {
        // 1) أزل الـ active عن كل الأزرار وأضفها للهذا الزر
        document.querySelectorAll('#main-meal-tab-customize .day-option-customize')
          .forEach(b => {
            b.classList.remove('bg-green-600','text-white');
          });
        btn.classList.add('bg-green-600','text-white');

        // 2) حدّد المتغير
        selectedCustomizeDay = btn.dataset.day;

        // 3) reset الوجبة إلى breakfast
        selectedCustomizeMeal = 'breakfast';

        // 4) حدّث الأيقونات والأعداد
        setInitialActivesCustomize();    // لخلفية الزر والصح للوجبة
        updateCustomizeDayCounts();       // لأرقام الأيام
        renderMealsCustomize();           // إعادة رسم الوجبات
updateCustomizeDurationCount()
        // 5) force اختيار زر Breakfast في الواجهة
        document.querySelector(
          '#main-meal-tab-customize .meal-btn-customize[data-meal="breakfast"]'
        )?.click();
      };
    });

  // وجبات Customize (يبقى كما هو)
  document.querySelectorAll('#main-meal-tab-customize .meal-btn-customize')
    .forEach(btn => {
      btn.onclick = () => {
        selectedCustomizeMeal = btn.dataset.meal;

          setInitialActivesCustomize();
      bindCustomizeWeekControls();
      bindCustomizeDurationControls();
    updateCustomizeDurationCount()
      renderMealsCustomize();
       updateSelectedMealCard();
  updateCustomizeDayCounts();
      };
    });
}

  // ==== Set “active” classes ====
function setInitialActivesCustomize() {
  // 1) Active style للزر اللي مختار حالياً
  document
    .querySelectorAll('#main-meal-tab-customize .meal-btn-customize')
    .forEach(btn => {
      const isActive = btn.dataset.meal === selectedCustomizeMeal;
      btn.classList.toggle('bg-green-600', isActive);
      btn.classList.toggle('text-white',    isActive);
    });

  // 2) Checkmarks للوجبات المخزنة
  const store = JSON.parse(localStorage.getItem('customizeMeals')) || {};
  const saved = (store[selectedLevel] || {})[selectedCustomizeDay] || {};

  document
    .querySelectorAll('#main-meal-tab-customize .meal-btn-customize')
    .forEach(btn => {
      const icon = btn.querySelector('.number-test-Meal');
      if (icon) {
        if (saved[btn.dataset.meal]) icon.classList.remove('hidden');
        else                         icon.classList.add('hidden');
      }
    });


}

  // ==== Render Customize Meals Into Accordion ====
  function renderMealsCustomize() {
    const container = document.getElementById('meal-options-accordion-customize');
    container.innerHTML = '';

      const store = JSON.parse(localStorage.getItem('customizeMeals')) || {};
  const savedId =
    (store[selectedLevel] || {})[selectedCustomizeDay] || {}
  [selectedCustomizeMeal];
    // **No language sub-key**: data[selectedDay][selectedMeal] is already an array
    const arr = ((currentCustomizeData||{})[selectedCustomizeDay]||{})[selectedCustomizeMeal] || [];

    if (!arr.length) {
      container.innerHTML = '<p class="text-center text-gray-500">لا توجد وجبات</p>';
      return;
    }

arr.forEach(item => {
  const card = document.createElement('div');
  card.className = 'meal-option mb-2 rounded p-4 meal-active';
  card.dataset.mealId = item.id;
  card.innerHTML = `
      <img src="./cart.gif" alt="${item.title}" class="meal-image w-full h-auto rounded mb-2" />
      <div class="meal-desc content">
        <h4 class="font-bold text-lg mb-2">${item.title}</h4>
        <p class="text-gray-600">${item.description}</p>
      </div>
  `;

  // إذا هذه الوجبة محفوظة مسبقاً، خَلِّها selected
  if (item.id === savedId) {
    card.classList.add('selected-meal');
  }

  card.onclick = () => {
    // 1) نظّف التحديد القديم
    container.querySelectorAll('.meal-option')
      .forEach(c => c.classList.remove('selected-meal'));

    // 2) ضَع الـ class على الذي نقرته
    card.classList.add('selected-meal');

    // 3) افتح الـ modal لاختيار الـ radio
    renderAccordion(arr);
  };

  container.appendChild(card);
});
  }

  // ==== Accordion Modal (same as before) ====
  function renderAccordion(mealOptions) {
    let target = document.querySelector('.meal-section');
    target.innerHTML = '';
    if (!mealOptions.length) {
      target.innerHTML = '<p class="text-center text-gray-500">No meals available</p>';
      return;
    }
    const newT = target.cloneNode(true);
    target.parentNode.replaceChild(newT, target);
    target = newT;

    const userData = JSON.parse(localStorage.getItem('customizeMeals'))||{};
    const selectedMeals = userData[selectedLevel]?.[`week${weekDuration}`]?.[selectedCustomizeDay]||{};

    let dlg = document.querySelector('.meal-dialog');
    if (!dlg) {
      dlg = document.createElement('dialog');
      dlg.className = 'meal-dialog rounded-lg shadow-lg p-4 border min-h-80';
      dlg.innerHTML = `
     <div class="dialog-container">
                          <button class="close-dialog">×</button>
                          <form class="form-horizontal" id="ModalForm" method="post">
                            <div class="modal-body">
                              <input type="number" hidden value="1" id="myBox" />
                              <div id="mealsContainer"></div>
                            </div>
                            <div class="submit-menu-btn-div">
                              <button type="button" class="menu-submit" data-dismiss="modal" id="submit">Save</button>
                            </div>
                          </form>
                        </div>
      `;
      document.body.appendChild(dlg);
    }

    const mc = dlg.querySelector('#mealsContainer');
    mc.innerHTML = '';
    mealOptions.forEach(m => {
      const isChecked = Object.values(selectedMeals).includes(m.id) ? 'checked' : '';
      mc.insertAdjacentHTML('beforeend', `
       <div class="meals" data-meal-id="${m.id}" style="margin-bottom: 1em;">
                              <input type="number" class="BoxNo" hidden value="1" />
                              <div class="circle">-</div>
                              <div class="meal-data">
                                <img class="dialog-image rounded-lg" src="${m.image}" alt="${m.title}" width="150" height="150" />
                                <div class="meal-info">
                                  <div class="meal-name-macros">
                                    <p class="meal-name dialog-title">${m.title}</p>
                                    <div class="meal-macros">
                                      <div class="macros">
                                        <div class="cal">
                                          <input class="num Kcal" value="60" readonly style="border: none; text-align: center" />
                                          <div class="title">kcal</div>
                                        </div>
                                        <div class="cal">
                                          <input class="num fat" value="1.1" readonly style="border: none; text-align: center" />
                                          <div class="title">fat</div>
                                        </div>
                                        <div class="cal">
                                          <input class="num protein" value="4.1" readonly style="border: none; text-align: center" />
                                          <div class="title">protein</div>
                                        </div>
                                        <div class="cal">
                                          <input class="num carb" value="7.9" readonly style="border: none; text-align: center" />
                                          <div class="title">carb</div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="about-meal">${m.description}</div>
                                  <div class="meal-choice">
                                    <div class="input-field">
                                      <div class="checkbox-wrapper">
                                        <input id="meal-${m.id}" class="inputss meal-radio" name="selectedMeal" type="radio" value="${m.id}" ${isChecked} />
                                        <label class="terms-label" for="meal-${m.id}">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 200 200" class="checkbox-svg">
                                            <mask fill="white" id="path-1-inside-${m.id}">
                                              <rect height="200" width="200"></rect>
                                            </mask>
                                            <rect mask="url(#path-1-inside-${m.id})" stroke-width="40" class="checkbox-box" height="200" width="200"></rect>
                                            <path stroke-width="15" d="M52 111.018L76.9867 136L149 64" class="checkbox-tick"></path>
                                          </svg>
                                          <span class="label-text">Add meal</span>
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
      `);
    });

    mc.querySelectorAll('input[type=radio]').forEach(radio => {
      radio.onchange = () => {
        mc.querySelectorAll('.meals').forEach(div => {
          div.classList.remove('selected');
          div.querySelector('.circle').textContent = '-';
        });
        const cur = radio.closest('.meals');
        cur.classList.add('selected');
        cur.querySelector('.circle').textContent = '✔';
        storeMealInLocalStorage(radio.value, weekDuration, dayDuration);
        updateCustomizeDayCounts();
      };
      if (radio.checked) radio.dispatchEvent(new Event('change'));
    });

    dlg.querySelector('.close-dialog').onclick = () => dlg.close();
    dlg.querySelector('.menu-submit').onclick = () => dlg.close();
    dlg.showModal();
  }

  // ==== Store to localStorage ====
  function storeMealInLocalStorage(id,wDur,dDur){
    storeCustomizeMealInLocalStorage(id, wDur, dDur);
  }

  // ==== Hook up “Customize” card click ====
  document.querySelector('[data-type="customize"]')
    .addEventListener('click', function(){ handleCustomize(this); });



/**
 * يخزّن وجبة الـ Customize في localStorage،
 * ثم يفعل الزرّ المناسب في واجهة Customize.
 */

function updateCustomizeDayCounts() {
  const store = JSON.parse(localStorage.getItem('customizeMeals')) || {};
  const levelData = store[selectedLevel] || {};

  document
    .querySelectorAll('#main-meal-tab-customize .day-option-customize')
    .forEach(btn => {
      const day = btn.dataset.day;
      const icon = btn.querySelector('.number-test-day');
      const count = Object.keys(levelData[day] || {}).length;
      if (!icon) return;

      if (count >= 5) {
        icon.textContent = '✔';
      } else {
        icon.textContent = `${count}/5`;
      }
      // تأكد أنه ظاهر دائمًا
      icon.classList.remove('hidden');
    });
}




  function updateCustomizeDurationCount() {
    const store = JSON.parse(localStorage.getItem('customizeMeals')) || {};
    const plan  = store[selectedLevel] || {};
    // حدد قائمة الأيام الفعلية للخطة
    const daysList = dayDuration === 5
      ? ['sunday','monday','tuesday','wednesday','thursday']
      : ['saturday','sunday','monday','tuesday','wednesday','thursday'];
    // عدّ الأيام التي تحوي 5 وجبات
    let completed = daysList.filter(d =>
      plan[d] && Object.keys(plan[d]).length === 5
    ).length;

    // حدّد الأيقونة وابن نصّها
    const btn       = document.querySelector(
      `#main-meal-tab-customize .duration-btn[data-duration="${dayDuration}"]`
    );
    const icon      = btn?.querySelector(
      dayDuration === 6
        ? '.number-test-duration-6'
        : '.number-test-duration-5'
    );
    if (!icon) return;

    if (completed === daysList.length) {
      icon.textContent = '✔';
    } else {
      icon.textContent = `${completed}/${daysList.length}`;
    }
  }








function updateSelectedMealCard() {
  // جلب الوجبة المسجّلة لليوم الحالي
  const store   = JSON.parse(localStorage.getItem('customizeMeals')) || {};
  const savedId = (store[selectedLevel]?.[`week${weekDuration}`] || {})[selectedCustomizeDay]?.[selectedCustomizeMeal];
  // حدّد كل البطاقات:
  document
    .querySelectorAll('#meal-options-accordion-customize .meal-option')
    .forEach(card => {
      // إذا الـ data-meal-id يساوي savedId → أضف الكلاس
      if (card.dataset.mealId === savedId) {
        card.classList.add('selected-meal');
      } else {
        card.classList.remove('selected-meal');
      }
    });
}






function storeCustomizeMealInLocalStorage(mealId, selectedWeekDuration = weekDuration, selectedDayDuration = dayDuration) {
  const parts = String(mealId || '').split('_');
  const level = parts[0];
  const weekKey = parts[1];
  const day = parts[2];
  const mealType = parts[3];

  if (!level || !weekKey || !day || !mealType) {
    return;
  }

  let store = JSON.parse(localStorage.getItem('customizeMeals')) || {};
  store[level] = store[level] || {};
  store[level][weekKey] = store[level][weekKey] || {};
  store[level][weekKey][day] = store[level][weekKey][day] || {};
  store[level][weekKey][day][mealType] = mealId;
  localStorage.setItem('customizeMeals', JSON.stringify(store));
  localStorage.removeItem('meals');
  selectedCustomizeMeal = mealType;
  setInitialActivesCustomize();
  updateSelectedMealCard();
  updateCustomizeDayCounts();
  updateCustomizeDurationCount();
}






function handleCustomizeSaveClickPlan2(event) {
  event?.preventDefault?.();

  const storeOrig = JSON.parse(localStorage.getItem('customizeMeals')) || {};
  const level = selectedLevel;
  const dayList = dayDuration === 6
    ? ['saturday','sunday','monday','tuesday','wednesday','thursday']
    : ['sunday','monday','tuesday','wednesday','thursday'];
  const allowedWeekKeys = Array.from({ length: weekDuration }, (_, index) => `week${index + 1}`);

  const incompleteWeeks = allowedWeekKeys.filter((weekKey) => {
    const weekData = storeOrig[level]?.[weekKey] || {};
    return !dayList.every((day) => Object.keys(weekData[day] || {}).length >= 5);
  });

  if (incompleteWeeks.length > 0) {
    Swal.fire(
      'Incomplete Package',
      `You must complete all required meals for ${dayDuration} days before saving this customized plan.`,
      'warning'
    );
    return;
  }

  const snapshot = {
    days: dayDuration,
    weeks_count: allowedWeekKeys.length || weekDuration,
    packageName: `Customized ${level} Calories`,
  };

  allowedWeekKeys.forEach((weekKey) => {
    const weekMeals = storeOrig[level]?.[weekKey] || {};
    const filtered = {};

    dayList.forEach((day) => {
      if (weekMeals[day]) {
        filtered[day] = weekMeals[day];
      }
    });

    if (Object.keys(filtered).length > 0) {
      snapshot[weekKey] = filtered;
    }
  });

  const saveStore = JSON.parse(localStorage.getItem('customizeMealsSave')) || {};
  saveStore[level] = snapshot;
  localStorage.setItem('customizeMealsSave', JSON.stringify(saveStore));
  window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
  renderAllPackages?.();
  updateCartCount?.();
  toggleMiniCartButton?.();

  Swal.fire('Saved', 'Your customized plan was saved successfully.', 'success');
}

document.querySelector('.save-button')
  ?.addEventListener('click', handleCustomizeSaveClickPlan2);

document.getElementById('standerConfirmYes')
  .addEventListener('click', () => {
    const allData      = window._allStanderData || {};
    const daysList = selectedStanderDurationDays === 6
      ? ['saturday','sunday','monday','tuesday','wednesday','thursday']
      : ['sunday','monday','tuesday','wednesday','thursday'];
    const newWeeks = {};

    for (let i = 1; i <= selectedStanderDurationWeeks; i++) {
      const weekKey = `week${i}`;
      const plan = allData[selectedLevel]?.[weekKey] || {};
      const filtered = {};

      daysList.forEach((day) => {
        filtered[day] = {};
        Object.entries(plan[day] || {}).forEach(([mealType, mealArr]) => {
          const meal = (mealArr.en || [])[0];
          if (meal) {
            filtered[day][mealType] = meal.id;
          }
        });
      });

      newWeeks[weekKey] = filtered;
    }

    const priceWithoutVat = resolveSubscriptionSubtotal(
      selectedLevel,
      selectedStanderDurationDays,
      selectedStanderDurationWeeks
    );

    const freshStore = {
      [selectedLevel]: {
      packageName: allData[selectedLevel]?.packaging_Name || `${selectedLevel} Calories`,
      weeks_count: selectedStanderDurationWeeks,
      days:  selectedStanderDurationDays,
      price_without_vat: priceWithoutVat,
      price_with_vat: Number((priceWithoutVat * (1 + window.subscriptionVatRate)).toFixed(2)),
      ...newWeeks
      }
    };

    localStorage.setItem('standerdPackages', JSON.stringify(freshStore));
    window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
    renderAllPackages?.();
    updateCartCount?.();
    toggleMiniCartButton?.();
    document.querySelector('.overlaystander').classList.remove('active');
    document.querySelector('.menuWrapper').classList.remove('active');
  });







</script>
<!-- ===== Customize Tab Script ===== -->



@endpush
</section>
