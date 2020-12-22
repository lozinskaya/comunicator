//
//  SettingsViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 05.12.2020.
//

import Foundation
import UIKit

class ProfileController: UIViewController {
    static var current : ProfileController = ProfileController();
    static var showed = false;
    @IBOutlet weak var balanceLabel: UILabel!
    @IBOutlet weak var nameLabel: UILabel!
    @IBOutlet weak var emailLabel: UILabel!
    func reloadBalance() {
        balanceLabel.text = Global.balance + " ₽";
    }
    
    override func viewDidLoad() {
        ProfileController.current = self;
        ProfileController.showed = true;
        super.viewDidLoad()
        nameLabel.text = (Global.userinfo["firstname"] as? String ?? "") + " " + (Global.userinfo["surname"] as? String ?? "");
        reloadBalance();
        emailLabel.text = Global.userinfo["email"] as? String;
    }
    
    override func viewWillAppear(_ animated: Bool) {

        reloadBalance();
    }

}
