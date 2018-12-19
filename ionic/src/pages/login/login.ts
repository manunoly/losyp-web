import {Component} from '@angular/core';
import {
  IonicPage,
  NavController,
  LoadingController,
  ToastController,
  AlertController

} from "ionic-angular";
import {User} from '../../models/user';
import {AuthProvider} from '../../providers/auth/auth';
import {HomePage} from '../home/home';
import { TabPage } from '../tab/tab';
import { NotificacionesPushProvider } from '../../providers/notificaciones-push/notificaciones-push';
import { FCM } from "@ionic-native/fcm";
// import { SignupPage } from '../signup/signup';
import { Facebook } from '@ionic-native/facebook';

@IonicPage()
@Component({
  selector: 'page-login',
  templateUrl: 'login.html',
})
export class LoginPage {
  user: User;
  loading: any;
  showPassword: boolean = false;

  constructor(public navCtrl: NavController,
              public authService: AuthProvider,
              public toastCtrl: ToastController,
              public load: LoadingController,
              public push: NotificacionesPushProvider,
              private fb: Facebook,
              public fcm: FCM,
              public alertCtrl: AlertController) {
    this.user = new User();


  }

  loginFacebook(){
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
    this.fb.api('/me?fields=id,name,email,first_name,picture,last_name,gender',['public_profile','email'])
    .then(data=>{
      this.user.phoneos = this.push.getOS();
      this.user.name = data['name'];
      this.user.email = data['email'];
      this.user.password = "";
      this.user.is_facebook = true;
      this.doLogin(true);
    })
    .catch(error =>{
      console.error( error );
    });
  }

  showPrompt() {
    let prompt = this.alertCtrl.create({
      title: 'Olvido de contraseña',
      message: "Escribe tu dirección de email",
      enableBackdropDismiss: false,
      inputs: [
        {
          name: 'email',
          type: 'email'
        },
      ],
      buttons: [
        {
          text: 'Cancel',
          role: 'cancel'

        },
        {
          text: ' Enviarme contraseña',
          handler: data => {
            if (this.authService.validateEmail(data.email)) {
              // const navTransition = prompt.dismiss();

              // start some async method
              this.authService.forgot_password(data.email).then((respose) => {
                console.log("no dio error",respose);

                let toast = this.toastCtrl.create({
                  message: "La contraseña se ha enviado a su correo",
                  duration: 5000,
                  position: 'bottom',
                  showCloseButton: true,
                  closeButtonText: "Cerrar"
                });
                toast.present();
                // navTransition.then(() => {
                //   this.navCtrl.pop();
                // });
              }).catch(
                (error) => {
                  console.log("error error",error);
                  let toast = this.toastCtrl.create({
                    message: "No se pudo enviar la contraseña",
                    duration: 5000,
                    position: 'bottom',
                    showCloseButton: true,
                  });
                  toast.present();
                }
              );


            }
            else {
              return false;
            }


          }
        }
      ]
    });
    prompt.present();
  }

  doLogin(face= false) {
    this.loading = this.load.create({
      content: "Autenticando..."
    });
    this.loading.present();
    this.authService.login(this.user, face)
      .then(result => {
        if (result === true) {
          this.loading.dismiss();
          this.push.checkTokenMovil();
          this.push.subcribe();
          this.navCtrl.push(TabPage);
          //  this.navCtrl.pop();
        } else {
          let toast = this.toastCtrl.create({
            message: "Correo y/o contraseña incorrectos",
            duration: 5000,
            position: 'bottom',
            showCloseButton: true,
            closeButtonText: "Cerrar"
          });
          toast.present();
          this.loading.dismiss();
        }
      }).catch(
      (error) => {
        let toast = this.toastCtrl.create({
          message: "Error Inesperado!",
          duration: 5000,
          position: 'bottom',
          showCloseButton: true,
        });
        toast.present();
        this.loading.dismiss();
        // this.navCtrl.goToRoot({});
        this.navCtrl.push(TabPage, {
          connetionDown: true
        });
        // this.navCtrl.pop();
      }
    );
  }


  llenarCampos() {
    let toast = this.toastCtrl.create({
      message: "Debe llenar los campos correctamente",
      duration: 5000,
      position: 'bottom',
      showCloseButton: true,
      closeButtonText: "Cerrar"
    });
    toast.present();
  }

  goToSignup() {
    this.navCtrl.push("SignupPage");
    // this.navCtrl.push(SignupPage);
  }

}

