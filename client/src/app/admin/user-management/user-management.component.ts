import { Component, OnInit } from '@angular/core';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap/modal';
import { RolesModalComponent } from 'src/app/modals/roles-modal/roles-modal.component';
import { User } from 'src/app/_models/user';
import { AdminService } from 'src/app/_services/admin.service';

@Component({
  selector: 'app-user-management',
  templateUrl: './user-management.component.html',
  styleUrls: ['./user-management.component.scss']
})
export class UserManagementComponent implements OnInit {
  users: User[]=[];
  bsModalRef: BsModalRef<RolesModalComponent> = new BsModalRef<RolesModalComponent>();
  availableRoles = [
    'Admin',
    'Moderator',
    'User'
  ]

  constructor(private adminService: AdminService, private modalService: BsModalService){

  }

  getUsersWithRoles(){
    this.adminService.getUsersWithRoles().subscribe({
      next: users => this.users = users
    })
  }

  ngOnInit(): void{
    this.getUsersWithRoles();
  }

  openRolesModal(user: User) {
    const config = {
      class: 'modal-dialog-centered',
      initialState: {
        username: user.username,
        availableRoles: this.availableRoles,
        selectedRole: user.role
      }
    }
    this.bsModalRef = this.modalService.show(RolesModalComponent, config);
    this.bsModalRef.onHide?.subscribe({
      next: () => {
        const selectedRole = this.bsModalRef.content?.selectedRole;
        if (selectedRole !== user.role && selectedRole) {
          this.adminService.updateUserRoles(user.username, selectedRole).subscribe({
            next: role => user.role = role
          })
        }
      }
    })
  }
}
