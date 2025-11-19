// le btn de chargement 
const Charger=document.getElementById("uploadPochette");
Charger.addEventListener("click",lanceParcourir,false);

//le champ input file
const Upload=document.getElementById("album_imageFile")

//l'image affichée
const ImageAffichee=document.getElementById("imageAffichee");
Upload.addEventListener("change",afficheImage,false);

function lanceParcourir(){
    Upload.click();
}
function afficheImage(){
    const imageChargee = this.files[0];  //le fichier choisi
    const urlImageChargee = URL.createObjectURL(imageChargee); //crée une URL temporaire pour l'image
    ImageAffichee.setAttribute("src",urlImageChargee); //modifie la source de l'image affichée
}