import { Component } from '@angular/core';
import { BsModalRef } from 'ngx-bootstrap/modal';

@Component({
  selector: 'app-bans-modal',
  templateUrl: './bans-modal.component.html',
  styleUrls: ['./bans-modal.component.scss']
})
export class BansModalComponent {
  username = '';
  availableRoles: any[] = [];

  constructor(public bsModalRef: BsModalRef){ }

  update(event: Event) {
    const value = (event.target as HTMLSelectElement).value;
  }
}
