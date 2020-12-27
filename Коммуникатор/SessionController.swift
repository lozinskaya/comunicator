//
//  SettingsViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 05.12.2020.
//

import Foundation
import UIKit

extension UIImageView {
    func load(url: URL) {
        DispatchQueue.global().async { [weak self] in
            if let data = try? Data(contentsOf: url) {
                if let image = UIImage(data: data) {
                    DispatchQueue.main.async {
                        self?.image = image
                    }
                }
            }
        }
    }
}

class SessionController: UIViewController {
    
    
    
    @IBOutlet weak var adviceLabel: UILabel!
    @IBOutlet weak var activSessionView: UIView!
    
    @IBOutlet weak var balanceLabel: UILabel!
    @IBOutlet weak var qrView: UIImageView!
    @IBOutlet weak var tariffLabel: UILabel!
    
    @IBOutlet weak var timeLabel: UILabel!
    @IBOutlet weak var countLabel: UILabel!
    
    var timer = Timer()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        qrView.load(url: WebFuncs.ActionUrl(action: "qrcode", params: ["code": "\(Global.userinfo["code"].description)"]));
        timerAction()
        timer = Timer.scheduledTimer(timeInterval: 30,target: self, selector: #selector(timerAction), userInfo: nil, repeats: true) 
    }
    
    @objc func timerAction() {
       WebFuncs.JSONRequest(action: "session", params: ["sessionkey": Global.sessionkey]) { result in
            debugPrint(result);
            if let result = result {
                DispatchQueue.main.async {
                    Global.sessioninfo = result;
                    Global.balance = "\(Global.sessioninfo["balance"].description)"
                    Global.is_active = Int(Global.sessioninfo["is_active"]) ?? 0
                    self.balanceLabel.text = Global.balance + " ₽"
                    self.activSessionView.isHidden = Global.is_active != 1
                    self.adviceLabel.text = Global.is_active == 1 ? "Чтобы завершить сеанс покажите QR-код администратору" : "Чтобы начать сеанс покажите QR-код администратору";
                    if(Global.is_active == 1) {
                        Global.activesession = Global.sessioninfo["active"] as! [String : AnyObject]
                        debugPrint(Global.activesession)
                        self.timeLabel.text = "\(Global.activesession["duration_min"].description)" + " мин"
                        let extra_count = (Int(Global.activesession["count"]) ?? 1) - 1
                        self.countLabel.text = extra_count == 0 ? "Я" : ("Я и еще " + "\(extra_count.description)")
                        self.tariffLabel.text = "1 мин = " + "\(Global.activesession["tariff_sum"].description)" + " ₽"
                    }
                }
            }
        }
    } 
}
