import { Component,ViewChild } from '@angular/core';
import { IonicPage, NavController, NavParams ,ToastController, LoadingController} from 'ionic-angular';
import {User} from '../../models/user';
import {AuthProvider} from '../../providers/auth/auth';
 import { HomePage } from "../home/home";
import {CondicionesPage} from "../condiciones/condiciones";
import { NotificacionesPushProvider } from './../../providers/notificaciones-push/notificaciones-push';
import { FCM } from "@ionic-native/fcm";
import { Facebook } from '@ionic-native/facebook';

@IonicPage()
@Component({
  selector: 'page-signup',
  templateUrl: 'signup.html',
})
export class SignupPage {
  user: User;
  error: string;
  condiciones:boolean;
  @ViewChild('f') f;

  constructor(public navCtrl: NavController,
    public navParams: NavParams,
    private push: NotificacionesPushProvider,
    private fcm: FCM,
    private fb: Facebook,
    public toastCtrl: ToastController, public load: LoadingController,
    public auth: AuthProvider ) {
      this.user = new User();
      this.user.phoneid = "";
      this.user.phoneos="ANDROID";
      this.condiciones = false;
  }

  ionViewDidLoad() {
    this.fcm.getToken().then(deviceID => {
      this.user.phoneid = deviceID;
      this.user.phoneos = this.push.getOS();
    });
  }

  openCondiciones(){
    this.navCtrl.push(CondicionesPage);
  }

  facebookRegister(){
    this.fb.login(['public_profile', 'email'])
    .then(rta => {
      if(rta.status == 'connected'){
        this.getInfo();
      };
    })
    .catch(error =>{
      let toast = this.toastCtrl.create({
        message: "Ha ocurrido un error conectando con Facebook!",
        duration: 5000,
        position: 'bottom',
        showCloseButton: true,
        closeButtonText: "Cerrar"
      });
      toast.present();
    });
  }

  getInfo(){
    this.fcm.getToken().then(deviceID=>{
      this.user.phoneid = deviceID;
    }).catch(_=>{});
    this.fb.api('/me?fields=id,name,email,first_name,picture,last_name,gender',['public_profile','email'])
    .then(data=>{
      this.user.phoneos = this.push.getOS();
      this.user.name = data['name'];
      this.user.email = data['email'];
      this.user.password = "";
      this.user.is_facebook = true;
      this.faceLogin();
    })
    .catch(error =>{
      console.error( error );
    });
  }

  faceLogin(){
    let loading = this.load.create({
      content: "Autenticando..."
    });
    loading.present();
    this.auth.login(this.user, true)
      .then(result => {
        console.log("login result", result);
        if (result === true) {
          loading.dismiss();
          this.push.checkTokenMovil();
          this.push.subcribe();
          this.navCtrl.setRoot(HomePage);
        } else {
          let toast = this.toastCtrl.create({
            message: "Problema de conexión",
            duration: 5000,
            position: 'bottom',
            showCloseButton: true,
            closeButtonText: "Cerrar"
          });
          toast.present();
          loading.dismiss();
        }
      }).catch(
      (error) => {
        let toast = this.toastCtrl.create({
          message: "Problema de conexión",
          duration: 5000,
          position: 'bottom',
          showCloseButton: true,
        });
        toast.present();
        loading.dismiss();
        // this.navCtrl.goToRoot({});
        this.navCtrl.push(HomePage, {
          connetionDown: true
        });
        // this.navCtrl.pop();
      }
    );
  }

  doSignUp(){
    if( this.user.name.trim() != ''){
      let loading = this.load.create({
        content:"Registrando..."
      });
      loading.present();
      this.auth.signUp(this.user)
      .then(
        (result) => {
          if (result === true) {
              this.push.subcribe();
               let toast = this.toastCtrl.create({
                message: "Se ha registrado satifactoriamente!",
                duration: 5000,
                position: 'bottom'
              });
              toast.present();
              if(this.user.phoneid == "") this.push.forceUpdateMovilId()
              else this.push.subcribe();
              this.navCtrl.setRoot(HomePage);
              //this.navCtrl.pop();
              } else {
                let toast = this.toastCtrl.create({
                  message: "Ese email ya está en uso, prueba recuperar tu contraseña",
                  duration: 5000,
                  position: 'top'
                });
                toast.present();
              }
              loading.dismiss();

        }
      ).catch(
        (error) => {
          let toast = this.toastCtrl.create({
            message:error ,
            duration: 5000,
            position: 'top'
          });
          toast.present();
          loading.dismiss();
        }
      );
    }else{
      let toast = this.toastCtrl.create({
        message: "Llene todos los campos para registrarse",
        duration: 5000,
        position: 'bottom',
        showCloseButton:true,
        closeButtonText:"Cerrar"
      });
      toast.present();
    }
  }

  llenarCampos(){

    if(!this.f.form.valid){
      let toast = this.toastCtrl.create({
        message: "Llene todos los campos para registrarse",
        duration: 5000,
        position: 'bottom',
        showCloseButton:true,
        closeButtonText:"Cerrar"
      });
      toast.present();
    }
    else{
      let toast = this.toastCtrl.create({
        message: "Las contraseñas deben coincidir",
        duration: 5000,
        position: 'bottom',
        showCloseButton:true,
        closeButtonText:"Cerrar"
      });
      toast.present();
    }

 }

}
