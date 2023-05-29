import {Component, OnInit} from '@angular/core';
import {AlertController, IonItemSliding, ToastController} from "@ionic/angular";
import {HowlerJsService} from "../../../services/howler-js.service";
import {SongModel} from "../../../model/song.model";
import {ApiUserService} from "../../../services/api-user.service";

@Component({
  selector: 'app-playlist',
  templateUrl: './playlist.page.html',
  styleUrls: ['./playlist.page.scss'],
})
export class PlaylistPage implements OnInit {
  songs: SongModel[];
  alertMessage: any;
  currentSong: SongModel;

  constructor(
    private apiUser: ApiUserService,
    private alertCtrl: AlertController,
    private toastCtrl: ToastController,
    private howler: HowlerJsService
  ) {
  }

  ngOnInit() {
    this.apiUser.fetchPlaylist();
    this.howler.activeSong.subscribe(song => this.currentSong = song);
    this.apiUser.songs.subscribe(songs => this.songs = songs);
  }

  async onDelete(song: any, itemSliding: IonItemSliding) {

    const alert = await this.alertCtrl.create({
      mode: 'ios',
      header: `Wanna remove the ${song.song_title} from liked songs?`,
      buttons: [
        {
          text: 'Cancel',
          role: 'cancel',
          cssClass: 'cancel',
          handler: () => {
            this.alertMessage = 'Alert canceled';
            itemSliding.close();
          },
        },
        {
          text: 'Oke gann',
          role: 'confirm',
          cssClass: 'delete',
          handler: () => {
            this.alertMessage = 'Song removed!';
            // this.playlistService.deleteSong(song.song_id);
            itemSliding.close();
            this.toastCtrl.create({
              message: this.alertMessage,
              duration: 1500,
              position: 'bottom',
            }).then(toastEl => {
              toastEl.present();
            });

          },
        },
      ]
    });
    await alert.present();
  }

}
