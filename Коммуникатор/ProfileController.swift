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
    
    @IBAction func ClikName(_ sender: Any) {
        let alert = UIAlertController(title: "Изменение имени", message: "Необходимо обратиться к администратору “Коммуникатора”", preferredStyle: UIAlertController.Style.alert)
        alert.addAction(UIAlertAction(title: "Так и сделаю", style: UIAlertAction.Style.default, handler: nil))
        self.present(alert, animated: true, completion: nil)
    }
    @IBAction func ClikEmail(_ sender: Any) {
        let alert = UIAlertController(title: "Изменение E-mail", message: "Необходимо обратиться к администратору “Коммуникатора”", preferredStyle: UIAlertController.Style.alert)
        alert.addAction(UIAlertAction(title: "Так и сделаю", style: UIAlertAction.Style.default, handler: nil))
        self.present(alert, animated: true, completion: nil)
    }
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
